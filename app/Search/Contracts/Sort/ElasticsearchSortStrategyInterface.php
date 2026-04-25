<?php

declare(strict_types=1);

namespace App\Search\Contracts\Sort;

interface ElasticsearchSortStrategyInterface
{
    public function build(): array;
}