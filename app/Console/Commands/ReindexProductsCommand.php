<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use App\Search\Contracts\ProductIndexerInterface;
use Illuminate\Console\Command;

class ReindexProductsCommand extends Command
{
    protected $signature = 'products:reindex';

    protected $description = 'Rebuild the Elasticsearch products index from scratch';

    public function handle(ProductIndexerInterface $indexer): int
    {
        $this->info('Creating index...');
        $indexer->createIndex();

        $total = Product::count();
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        Product::query()
            ->select(['id', 'name', 'price', 'category_id', 'in_stock', 'rating', 'created_at'])
            ->chunkById(500, function ($products) use ($indexer, $bar) {
                $indexer->bulkIndex($products);
                $bar->advance($products->count());
            });

        $bar->finish();
        $this->newLine();
        $this->info("Indexed {$total} products.");

        return self::SUCCESS;
    }
}