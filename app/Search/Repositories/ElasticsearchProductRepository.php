<?php

declare(strict_types=1);

namespace App\Search\Repositories;

use App\Search\DTO\SearchResultPage;
use App\Search\Exceptions\SearchException;
use App\Models\Product;
use App\Search\Contracts\Filters\ElasticsearchQueryFilterInterface;
use App\Search\Contracts\ProductRepositoryInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;
use App\Search\Sort\Elasticsearch\ElasticsearchSortStrategyRegistry;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Illuminate\Support\Facades\Log;

readonly class ElasticsearchProductRepository implements ProductRepositoryInterface
{
    /** @param ElasticsearchQueryFilterInterface[] $queryFilters */
    public function __construct(
        private Client $client,
        private string $index,
        private array $queryFilters,
        private ElasticsearchSortStrategyRegistry $sortRegistry,
    ) {}

    public function search(ProductSearchFiltersInterface $filters): SearchResultPage
    {
        try {
            $params = [
                'index' => $this->index,
                'body' => [
                    'query' => $this->buildQuery($filters),
                    'sort' => $this->sortRegistry->resolve($filters->getSort())->build(),
                    'size' => $filters->getPerPage() + 1,
                ],
            ];

            if ($filters->getCursor() !== null) {
                $params['body']['search_after'] = json_decode(
                    base64_decode($filters->getCursor()),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            }

            $response = $this->client->search($params)->asArray();
            $hits = $response['hits']['hits'];

            $hasMore = count($hits) > $filters->getPerPage();
            if ($hasMore) {
                array_pop($hits);
            }

            if (empty($hits)) {
                return new SearchResultPage(
                    items: collect(),
                    nextCursor: null,
                    prevCursor: null,
                    perPage: $filters->getPerPage(),
                );
            }

            $ids = array_column($hits, '_id');
            $idOrder = array_flip($ids);

            $products = Product::query()
                ->with('category')
                ->whereIn('id', $ids)
                ->get()
                ->sortBy(fn($p) => $idOrder[$p->id]);

            $nextCursor = $hasMore
                ? base64_encode(json_encode(end($hits)['sort']))
                : null;

            return new SearchResultPage(
                items: $products->values(),
                nextCursor: $nextCursor,
                prevCursor: null,
                perPage: $filters->getPerPage(),
            );
        } catch (ClientResponseException $e) {
            Log::error('Elasticsearch search failed', ['message' => $e->getMessage()]);

            throw new SearchException('Ошибка при выполнении поискового запроса', previous: $e);
        }
    }

    private function buildQuery(ProductSearchFiltersInterface $filters): array
    {
        $filter = [];

        foreach ($this->queryFilters as $queryFilter) {
            $queryFilter->apply($filter, $filters);
        }

        return [
            'bool' => [
                'must' => [['match' => ['name' => $filters->getQ()]]],
                'filter' => $filter,
            ],
        ];
    }
}
