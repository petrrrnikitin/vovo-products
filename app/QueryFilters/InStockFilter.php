<?php

declare(strict_types=1);

namespace App\QueryFilters;

use App\Contracts\QueryFilter\ProductQueryFilterInterface;
use App\Contracts\Search\ProductSearchFiltersInterface;
use Illuminate\Database\Eloquent\Builder;

class InStockFilter implements ProductQueryFilterInterface
{
    public function apply(Builder $query, ProductSearchFiltersInterface $filters): void
    {
        if ($filters->getInStock() !== null) {
            $query->where('in_stock', $filters->getInStock());
        }
    }
}