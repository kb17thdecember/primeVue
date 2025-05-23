<?php

namespace Modules\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Contracts\Services\ProductService;
use Modules\CMS\Services\CategoryServiceImpl;
use Modules\CMS\Services\ProductServiceImpl;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    public array $bindings = [
        ProductService::class => ProductServiceImpl::class,
        CategoryService::class => CategoryServiceImpl::class,
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
