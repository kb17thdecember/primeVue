<?php

namespace Modules\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CMS\Contracts\Services\AuthService;
use Modules\CMS\Contracts\Services\BrandService;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Contracts\Services\ProductService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Services\AuthServiceImpl;
use Modules\CMS\Services\BrandServiceImpl;
use Modules\CMS\Services\CategoryServiceImpl;
use Modules\CMS\Services\ProductServiceImpl;
use Modules\CMS\Services\StorageServiceImpl;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    public array $bindings = [
        ProductService::class => ProductServiceImpl::class,
        BrandService::class => BrandServiceImpl::class,
        CategoryService::class => CategoryServiceImpl::class,
        StorageService::class => StorageServiceImpl::class,
        AuthService::class => AuthServiceImpl::class,
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
