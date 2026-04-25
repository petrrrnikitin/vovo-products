<?php

declare(strict_types=1);

namespace App\Search\Sort\Elasticsearch;

use App\Search\Contracts\Sort\ElasticsearchSortStrategyInterface;

class DefaultStrategy implements ElasticsearchSortStrategyInterface
{
    public function build(): array
    {
        return [['_score' => 'desc'], ['id' => 'desc']];
    }
}