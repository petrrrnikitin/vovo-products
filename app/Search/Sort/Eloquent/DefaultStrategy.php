<?php

declare(strict_types=1);

namespace App\Search\Sort\Eloquent;

use App\Search\Contracts\Sort\SortStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class DefaultStrategy implements SortStrategyInterface
{
    public function apply(Builder $query): void
    {
        $query->orderByDesc('id');
    }
}