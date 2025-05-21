<?php

namespace Modules\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CMS\Contracts\Services\ProductService;
use Modules\CMS\Services\ProductServiceImpl;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * @var array|class-string[]
     */
    public array $bindings = [
        ProductService::class => ProductServiceImpl::class,
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
