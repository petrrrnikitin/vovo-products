<?php

declare(strict_types=1);

namespace App\Search\Filters\Elasticsearch;

use App\Search\Contracts\Filters\ElasticsearchQueryFilterInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;

class InStockFilter implements ElasticsearchQueryFilterInterface
{
    public function apply(array &$filter, ProductSearchFiltersInterface $filters): void
    {
        if ($filters->getInStock() !== null) {
            $filter[] = ['term' => ['in_stock' => $filters->getInStock()]];
        }
    }
}