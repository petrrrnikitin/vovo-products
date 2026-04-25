<?php

declare(strict_types=1);

namespace App\Search\Contracts;

use App\Models\Product;

interface ProductIndexerInterface
{
    public function index(Product $product): void;

    public function delete(int $id): void;

    /** @param iterable<Product> $products */
    public function bulkIndex(iterable $products): void;

    public function createIndex(): void;
}