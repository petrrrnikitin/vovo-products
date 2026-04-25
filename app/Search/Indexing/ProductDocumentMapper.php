<?php

declare(strict_types=1);

namespace App\Search\Indexing;

use App\Models\Product;

class ProductDocumentMapper
{
    public function toDocument(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'category_id' => $product->category_id,
            'in_stock' => $product->in_stock,
            'rating' => $product->rating,
            'created_at' => $product->created_at->toISOString(),
        ];
    }
}