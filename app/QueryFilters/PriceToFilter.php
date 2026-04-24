<?php

declare(strict_types=1);

namespace App\QueryFilters;

use App\Contracts\QueryFilter\ProductQueryFilterInterface;
use App\Contracts\Search\ProductSearchFiltersInterface;
use Illuminate\Database\Eloquent\Builder;

class PriceToFilter implements ProductQueryFilterInterface
{
    public function apply(Builder $query, ProductSearchFiltersInterface $filters): void
    {
        if ($filters->getPriceTo() !== null) {
            $query->where('price', '<=', $filters->getPriceTo());
        }
    }
}