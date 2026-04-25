<?php

declare(strict_types=1);

namespace App\Search\Sort\Elasticsearch;

use App\Search\Contracts\Sort\ElasticsearchSortStrategyInterface;

class RatingDescStrategy implements ElasticsearchSortStrategyInterface
{
    public function build(): array
    {
        return [['rating' => 'desc'], ['id' => 'desc']];
    }
}