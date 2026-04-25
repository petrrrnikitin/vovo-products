<?php

declare(strict_types=1);

namespace App\Search\Contracts\Sort;

use Illuminate\Database\Eloquent\Builder;

interface SortStrategyInterface
{
    public function apply(Builder $query): void;
}