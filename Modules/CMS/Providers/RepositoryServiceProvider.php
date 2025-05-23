<?php

namespace Modules\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CMS\Contracts\Repositories\CategoryRepository;
use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Repositories\CategoryRepositoryImpl;
use Modules\CMS\Repositories\ProductRepositoryImpl;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ProductRepository::class => ProductRepositoryImpl::class,
        CategoryRepository::class => CategoryRepositoryImpl::class,
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
