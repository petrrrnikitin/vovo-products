<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repository\ProductRepositoryInterface;
use App\Contracts\Search\ProductSearchInterface;
use App\QueryFilters\CategoryFilter;
use App\QueryFilters\FullTextFilter;
use App\QueryFilters\InStockFilter;
use App\QueryFilters\PriceFromFilter;
use App\QueryFilters\PriceToFilter;
use App\QueryFilters\RatingFromFilter;
use App\Repositories\EloquentProductRepository;
use App\Services\Search\EloquentProductSearch;
use App\Sort\SortStrategyRegistry;
use App\SortStrategies\DefaultStrategy;
use App\SortStrategies\NewestStrategy;
use App\SortStrategies\PriceAscStrategy;
use App\SortStrategies\PriceDescStrategy;
use App\SortStrategies\RatingDescStrategy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SortStrategyRegistry::class, fn() => new SortStrategyRegistry(
            strategies : [
                'price_asc' => new PriceAscStrategy(),
                'price_desc' => new PriceDescStrategy(),
                'rating_desc' => new RatingDescStrategy(),
                'newest' => new NewestStrategy(),
            ],
            default : new DefaultStrategy(),
        ));

        $this->app->when(EloquentProductRepository::class)
            ->needs('$queryFilters')
            ->give([
                new FullTextFilter(),
                new CategoryFilter(),
                new PriceFromFilter(),
                new PriceToFilter(),
                new InStockFilter(),
                new RatingFromFilter(),
            ]);

        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(ProductSearchInterface::class, EloquentProductSearch::class);
    }

    public function boot(): void
    {
        //
    }
}
