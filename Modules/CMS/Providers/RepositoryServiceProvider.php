<?php

namespace Modules\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CMS\Contracts\Repositories\BrandRepository;
use Modules\CMS\Contracts\Repositories\CategoryRepository;
use Modules\CMS\Contracts\Repositories\ShopRepository;
use Modules\CMS\Repositories\BrandRepositoryImpl;
use Modules\CMS\Repositories\CategoryRepositoryImpl;
use Modules\CMS\Repositories\ProductRepositoryImpl;
use Modules\CMS\Repositories\ShopRepositoryImpl;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ShopRepository::class => ShopRepositoryImpl::class,
        CategoryRepository::class => CategoryRepositoryImpl::class,
        BrandRepository::class => BrandRepositoryImpl::class
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
