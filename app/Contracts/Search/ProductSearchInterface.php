<?php

declare(strict_types=1);

namespace App\Contracts\Search;

use Illuminate\Pagination\CursorPaginator;

interface ProductSearchInterface
{
    public function search(ProductSearchFiltersInterface $filters): CursorPaginator;
}