<?php

declare(strict_types=1);

namespace App\Http\Transformers;

use App\DTO\ProductSearchFilters;
use App\Http\Requests\ProductFilterRequest;

final class ProductSearchFiltersTransformer
{
    public function fromRequest(ProductFilterRequest $request): ProductSearchFilters
    {
        return new ProductSearchFilters(
            q : $request->string('q')->trim()->value() ?: null,
            priceFrom : $request->filled('price_from') ? $request->float('price_from') : null,
            priceTo : $request->filled('price_to') ? $request->float('price_to') : null,
            categoryId : $request->filled('category_id') ? $request->integer('category_id') : null,
            inStock : $request->filled('in_stock') ? $request->boolean('in_stock') : null,
            ratingFrom : $request->filled('rating_from') ? $request->float('rating_from') : null,
            sort : $request->string('sort')->value() ?: null,
            perPage : $request->integer('per_page', 20),
        );
    }
}
