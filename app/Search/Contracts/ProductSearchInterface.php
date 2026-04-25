<?php

declare(strict_types=1);

namespace App\Search\Contracts;

use App\Search\DTO\SearchResultPage;

interface ProductSearchInterface
{
    public function search(ProductSearchFiltersInterface $filters): SearchResultPage;
}