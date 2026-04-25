<?php

declare(strict_types=1);

namespace App\Search\Sort\Elasticsearch;

use App\Search\Contracts\Sort\ElasticsearchSortStrategyInterface;

class NewestStrategy implements ElasticsearchSortStrategyInterface
{
    public function build(): array
    {
        return [['created_at' => 'desc'], ['id' => 'desc']];
    }
}