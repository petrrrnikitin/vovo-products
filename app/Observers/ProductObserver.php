<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Search\Indexing\Jobs\DeleteProductJob;
use App\Search\Indexing\Jobs\IndexProductJob;

class ProductObserver
{
    public function created(Product $product): void
    {
        IndexProductJob::dispatch($product->id);
    }

    public function updated(Product $product): void
    {
        IndexProductJob::dispatch($product->id);
    }

    public function deleted(Product $product): void
    {
        DeleteProductJob::dispatch($product->id);
    }
}