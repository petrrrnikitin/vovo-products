<?php

declare(strict_types=1);

namespace App\Search\Repositories;

use App\Search\DTO\SearchResultPage;
use App\Search\Contracts\ProductRepositoryInterface;
use App\Search\Contracts\ProductSearchFiltersInterface;

readonly class HybridProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private EloquentProductRepository $eloquent,
        private ElasticsearchProductRepository $elasticsearch,
    ) {}

    public function search(ProductSearchFiltersInterface $filters): SearchResultPage
    {
        return $filters->getQ() !== null
            ? $this->elasticsearch->search($filters)
            : $this->eloquent->search($filters);
    }
}