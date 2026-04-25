<?php

declare(strict_types=1);

namespace App\Search\Indexing\Jobs;

use App\Search\Contracts\ProductIndexerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteProductJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $productId) {}

    public function handle(ProductIndexerInterface $indexer): void
    {
        $indexer->delete($this->productId);
    }
}