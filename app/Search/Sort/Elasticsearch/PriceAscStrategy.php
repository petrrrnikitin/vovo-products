<?php

declare(strict_types=1);

namespace App\Search\Sort\Elasticsearch;

use App\Search\Contracts\Sort\ElasticsearchSortStrategyInterface;

class PriceAscStrategy implements ElasticsearchSortStrategyInterface
{
    public function build(): array
    {
        return [['price' => 'asc'], ['id' => 'asc']];
    }
}