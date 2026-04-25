<?php

declare(strict_types=1);

namespace App\Search\Filters\Elasticsearch;

use App\Search\Contracts\Filters\ElasticsearchQueryFilterInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;

class CategoryFilter implements ElasticsearchQueryFilterInterface
{
    public function apply(array &$filter, ProductSearchFiltersInterface $filters): void
    {
        if ($filters->getCategoryId() !== null) {
            $filter[] = ['term' => ['category_id' => $filters->getCategoryId()]];
        }
    }
}