<?php

declare(strict_types=1);

namespace App\Providers;

use App\Search\Contracts\ProductIndexerInterface;
use App\Search\Contracts\ProductRepositoryInterface;
use App\Search\Contracts\ProductSearchInterface;
use App\Search\Filters\Elasticsearch\CategoryFilter as EsCategoryFilter;
use App\Search\Filters\Elasticsearch\InStockFilter as EsInStockFilter;
use App\Search\Filters\Elasticsearch\PriceRangeFilter;
use App\Search\Filters\Elasticsearch\RatingFromFilter as EsRatingFromFilter;
use App\Search\Filters\Eloquent\CategoryFilter;
use App\Search\Filters\Eloquent\InStockFilter;
use App\Search\Filters\Eloquent\PriceFromFilter;
use App\Search\Filters\Eloquent\PriceToFilter;
use App\Search\Filters\Eloquent\RatingFromFilter;
use App\Search\Indexing\ElasticsearchProductIndexer;
use App\Search\Indexing\ProductDocumentMapper;
use App\Search\ProductSearchService;
use App\Search\Repositories\ElasticsearchProductRepository;
use App\Search\Repositories\EloquentProductRepository;
use App\Search\Repositories\HybridProductRepository;
use App\Search\Sort\Elasticsearch\DefaultStrategy as EsDefaultStrategy;
use App\Search\Sort\Elasticsearch\ElasticsearchSortStrategyRegistry;
use App\Search\Sort\Elasticsearch\NewestStrategy as EsNewestStrategy;
use App\Search\Sort\Elasticsearch\PriceAscStrategy as EsPriceAscStrategy;
use App\Search\Sort\Elasticsearch\PriceDescStrategy as EsPriceDescStrategy;
use App\Search\Sort\Elasticsearch\RatingDescStrategy as EsRatingDescStrategy;
use App\Search\Sort\Eloquent\DefaultStrategy;
use App\Search\Sort\Eloquent\NewestStrategy;
use App\Search\Sort\Eloquent\PriceAscStrategy;
use App\Search\Sort\Eloquent\PriceDescStrategy;
use App\Search\Sort\Eloquent\RatingDescStrategy;
use App\Search\Sort\Eloquent\SortStrategyRegistry;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerEloquent();
        $this->registerElasticsearch();
    }

    private function registerEloquent(): void
    {
        $this->app->singleton(SortStrategyRegistry::class, fn() => new SortStrategyRegistry(
            strategies: [
                'price_asc'   => new PriceAscStrategy(),
                'price_desc'  => new PriceDescStrategy(),
                'rating_desc' => new RatingDescStrategy(),
                'newest'      => new NewestStrategy(),
            ],
            default: new DefaultStrategy(),
        ));

        $this->app->when(EloquentProductRepository::class)
            ->needs('$queryFilters')
            ->give([
                new CategoryFilter(),
                new PriceFromFilter(),
                new PriceToFilter(),
                new InStockFilter(),
                new RatingFromFilter(),
            ]);

        $this->app->bind(ProductRepositoryInterface::class, HybridProductRepository::class);
        $this->app->bind(ProductSearchInterface::class, ProductSearchService::class);
    }

    private function registerElasticsearch(): void
    {
        $this->app->singleton(Client::class, fn() => ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->build()
        );

        $this->app->singleton(ElasticsearchSortStrategyRegistry::class, fn() => new ElasticsearchSortStrategyRegistry(
            strategies: [
                'price_asc'   => new EsPriceAscStrategy(),
                'price_desc'  => new EsPriceDescStrategy(),
                'rating_desc' => new EsRatingDescStrategy(),
                'newest'      => new EsNewestStrategy(),
            ],
            default: new EsDefaultStrategy(),
        ));

        $this->app->when(ElasticsearchProductRepository::class)
            ->needs('$index')
            ->give(fn() => config('elasticsearch.index'));

        $this->app->when(ElasticsearchProductRepository::class)
            ->needs('$queryFilters')
            ->give([
                new EsCategoryFilter(),
                new PriceRangeFilter(),
                new EsInStockFilter(),
                new EsRatingFromFilter(),
            ]);

        $this->app->when(ElasticsearchProductIndexer::class)
            ->needs('$index')
            ->give(fn() => config('elasticsearch.index'));

        $this->app->singleton(ProductDocumentMapper::class);

        $this->app->bind(ProductIndexerInterface::class, ElasticsearchProductIndexer::class);
    }
}