<?php

declare(strict_types=1);

namespace App\Search;

use App\Search\DTO\SearchResultPage;
use App\Search\Contracts\ProductRepositoryInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;
use App\Search\Contracts\ProductSearchInterface;

readonly class ProductSearchService implements ProductSearchInterface
{
    public function __construct(private ProductRepositoryInterface $repository) {}

    public function search(ProductSearchFiltersInterface $filters): SearchResultPage
    {
        return $this->repository->search($filters);
    }
}
