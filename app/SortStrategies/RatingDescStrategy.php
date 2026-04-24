<?php

declare(strict_types=1);

namespace App\SortStrategies;

use App\Contracts\Sort\SortStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class RatingDescStrategy implements SortStrategyInterface
{
    public function apply(Builder $query): void
    {
        $query->orderByDesc('rating')->orderByDesc('id');
    }
}