<?php

declare(strict_types=1);

namespace App\Search\Repositories;

use App\Search\DTO\SearchResultPage;
use App\Search\Exceptions\SearchException;
use App\Models\Product;
use App\Search\Contracts\Filters\ProductQueryFilterInterface;
use App\Search\Contracts\ProductRepositoryInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;
use App\Search\Sort\Eloquent\SortStrategyRegistry;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

readonly class EloquentProductRepository implements ProductRepositoryInterface
{
    /** @param ProductQueryFilterInterface[] $queryFilters */
    public function __construct(
        private array $queryFilters,
        private SortStrategyRegistry $sortRegistry,
    ) {}

    public function search(ProductSearchFiltersInterface $filters): SearchResultPage
    {
        try {
            $query = Product::query()->with('category');

            foreach ($this->queryFilters as $filter) {
                $filter->apply($query, $filters);
            }

            $this->sortRegistry->resolve($filters->getSort())->apply($query);

            $paginator = $query->cursorPaginate($filters->getPerPage())->withQueryString();

            return new SearchResultPage(
                items: $paginator->getCollection(),
                nextCursor: $paginator->nextCursor()?->encode(),
                prevCursor: $paginator->previousCursor()?->encode(),
                perPage: $paginator->perPage(),
            );
        } catch (QueryException $e) {
            Log::error('Product search query failed', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
            ]);

            throw new SearchException('Ошибка при выполнении поискового запроса', previous: $e);
        }
    }
}