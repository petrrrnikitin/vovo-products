<?php

declare(strict_types=1);

namespace App\Search\Contracts\Filters;

use App\Search\Contracts\ProductSearchFiltersInterface;
use Illuminate\Database\Eloquent\Builder;

interface ProductQueryFilterInterface
{
    public function apply(Builder $query, ProductSearchFiltersInterface $filters): void;
}