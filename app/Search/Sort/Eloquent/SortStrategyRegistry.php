<?php

declare(strict_types=1);

namespace App\Search\Sort\Eloquent;

use App\Search\Contracts\Sort\SortStrategyInterface;

readonly class SortStrategyRegistry
{
    /** @param array<string, SortStrategyInterface> $strategies */
    public function __construct(
        private array $strategies,
        private SortStrategyInterface $default,
    ) {}

    public function resolve(?string $sort): SortStrategyInterface
    {
        return $this->strategies[$sort] ?? $this->default;
    }
}