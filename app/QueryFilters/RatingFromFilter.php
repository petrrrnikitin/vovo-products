<?php

declare(strict_types=1);

namespace App\QueryFilters;

use App\Contracts\QueryFilter\ProductQueryFilterInterface;
use App\Contracts\Search\ProductSearchFiltersInterface;
use Illuminate\Database\Eloquent\Builder;

class RatingFromFilter implements ProductQueryFilterInterface
{
    public function apply(Builder $query, ProductSearchFiltersInterface $filters): void
    {
        if ($filters->getRatingFrom() !== null) {
            $query->where('rating', '>=', $filters->getRatingFrom());
        }
    }
}