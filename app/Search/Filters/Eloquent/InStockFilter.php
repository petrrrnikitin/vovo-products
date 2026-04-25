<?php

declare(strict_types=1);

namespace App\Search\Filters\Eloquent;

use App\Search\Contracts\Filters\ProductQueryFilterInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;
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