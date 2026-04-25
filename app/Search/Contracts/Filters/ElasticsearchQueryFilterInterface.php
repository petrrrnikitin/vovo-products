<?php

declare(strict_types=1);

namespace App\Search\Contracts\Filters;

use App\Search\Contracts\ProductSearchFiltersInterface;

interface ElasticsearchQueryFilterInterface
{
    public function apply(array &$filter, ProductSearchFiltersInterface $filters): void;
}