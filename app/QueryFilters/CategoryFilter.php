<?php

declare(strict_types=1);

namespace App\QueryFilters;

use App\Contracts\QueryFilter\ProductQueryFilterInterface;
use App\Contracts\Search\ProductSearchFiltersInterface;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements ProductQueryFilterInterface
{
    public function apply(Builder $query, ProductSearchFiltersInterface $filters): void
    {
        if ($filters->getCategoryId() !== null) {
            $query->where('category_id', $filters->getCategoryId());
        }
    }
}