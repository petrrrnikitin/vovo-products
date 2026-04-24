<?php

declare(strict_types=1);

namespace App\Contracts\Repository;

use App\Contracts\Search\ProductSearchFiltersInterface;
use Illuminate\Pagination\CursorPaginator;

interface ProductRepositoryInterface
{
    public function search(ProductSearchFiltersInterface $filters): CursorPaginator;
}