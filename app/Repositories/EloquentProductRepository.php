<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\QueryFilter\ProductQueryFilterInterface;
use App\Contracts\Repository\ProductRepositoryInterface;
use App\Contracts\Search\ProductSearchFiltersInterface;
use App\Exceptions\SearchException;
use App\Models\Product;
use App\Sort\SortStrategyRegistry;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Log;

readonly class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * @param ProductQueryFilterInterface[] $queryFilters
     */
    public function __construct(
        private array $queryFilters,
        private SortStrategyRegistry $sortRegistry,
    ) {}

    public function search(ProductSearchFiltersInterface $filters): CursorPaginator
    {
        try {
            $query = Product::query()->with('category');

            foreach ($this->queryFilters as $filter) {
                $filter->apply($query, $filters);
            }

            $this->sortRegistry->resolve($filters->getSort())->apply($query);

            return $query->cursorPaginate($filters->getPerPage())->withQueryString();
        } catch (QueryException $e) {
            Log::error('Product search query failed', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
            ]);

            throw new SearchException('Ошибка при выполнении поискового запроса', previous: $e);
        }
    }
}