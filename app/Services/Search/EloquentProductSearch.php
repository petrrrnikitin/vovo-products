<?php

declare(strict_types=1);

namespace App\Services\Search;

use App\Contracts\Repository\ProductRepositoryInterface;
use App\Contracts\Search\ProductSearchFiltersInterface;
use App\Contracts\Search\ProductSearchInterface;
use Illuminate\Pagination\CursorPaginator;

class EloquentProductSearch implements ProductSearchInterface
{
    public function __construct(private readonly ProductRepositoryInterface $repository) {}

    public function search(ProductSearchFiltersInterface $filters): CursorPaginator
    {
        return $this->repository->search($filters);
    }
}