<?php

namespace Modules\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CMS\Contracts\Repositories\AdminRepository;
use Modules\CMS\Contracts\Repositories\BrandRepository;
use Modules\CMS\Contracts\Repositories\CategoryRepository;
use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Contracts\Repositories\SettingRepository;
use Modules\CMS\Contracts\Repositories\ShopFrequencyRepository;
use Modules\CMS\Contracts\Repositories\ShopRepository;
use Modules\CMS\Repositories\AdminRepositoryImpl;
use Modules\CMS\Contracts\Repositories\SubscriberHistoryRepository;
use Modules\CMS\Repositories\BrandRepositoryImpl;
use Modules\CMS\Repositories\CategoryRepositoryImpl;
use Modules\CMS\Repositories\ProductRepositoryImpl;
use Modules\CMS\Repositories\SettingRepositoryImpl;
use Modules\CMS\Repositories\ShopFrequencyRepositoryImpl;
use Modules\CMS\Repositories\ShopRepositoryImpl;
use Modules\CMS\Repositories\SubscriberHistoryRepositoryImpl;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ShopRepository::class => ShopRepositoryImpl::class,
        CategoryRepository::class => CategoryRepositoryImpl::class,
        BrandRepository::class => BrandRepositoryImpl::class,
        ProductRepository::class => ProductRepositoryImpl::class,
        AdminRepository::class => AdminRepositoryImpl::class,
        SubscriberHistoryRepository::class => SubscriberHistoryRepositoryImpl::class,
        SettingRepository::class => SettingRepositoryImpl::class,
        ShopFrequencyRepository::class => ShopFrequencyRepositoryImpl::class,
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
