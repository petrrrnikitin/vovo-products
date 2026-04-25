<?php

declare(strict_types=1);

namespace App\Search\Contracts;

use App\Search\DTO\SearchResultPage;

interface ProductRepositoryInterface
{
    public function search(ProductSearchFiltersInterface $filters): SearchResultPage;
}