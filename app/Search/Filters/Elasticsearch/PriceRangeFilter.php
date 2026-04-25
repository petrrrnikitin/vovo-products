<?php

declare(strict_types=1);

namespace App\Search\Filters\Elasticsearch;

use App\Search\Contracts\Filters\ElasticsearchQueryFilterInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;

class PriceRangeFilter implements ElasticsearchQueryFilterInterface
{
    public function apply(array &$filter, ProductSearchFiltersInterface $filters): void
    {
        $range = [];

        if ($filters->getPriceFrom() !== null) {
            $range['gte'] = $filters->getPriceFrom();
        }

        if ($filters->getPriceTo() !== null) {
            $range['lte'] = $filters->getPriceTo();
        }

        if (!empty($range)) {
            $filter[] = ['range' => ['price' => $range]];
        }
    }
}