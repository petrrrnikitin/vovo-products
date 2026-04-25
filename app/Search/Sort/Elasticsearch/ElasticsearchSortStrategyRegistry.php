<?php

declare(strict_types=1);

namespace App\Search\Sort\Elasticsearch;

use App\Search\Contracts\Sort\ElasticsearchSortStrategyInterface;

readonly class ElasticsearchSortStrategyRegistry
{
    /** @param array<string, ElasticsearchSortStrategyInterface> $strategies */
    public function __construct(
        private array $strategies,
        private ElasticsearchSortStrategyInterface $default,
    ) {}

    public function resolve(?string $sort): ElasticsearchSortStrategyInterface
    {
        return $this->strategies[$sort] ?? $this->default;
    }
}