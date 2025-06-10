<?php

namespace Modules\Ecommerce\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Ecommerce\Contracts\Repositories\ProductRepository;
use Modules\Ecommerce\Repositories\ProductRepositoryImpl;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ProductRepository::class => ProductRepositoryImpl::class,
    ];
    /**
     * Register the service provider.
     */
    public function register(): void {}

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
