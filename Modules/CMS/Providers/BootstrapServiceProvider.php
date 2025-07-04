<?php

namespace Modules\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CMS\Contracts\Services\AdminService;
use Modules\CMS\Contracts\Services\AuthService;
use Modules\CMS\Contracts\Services\BrandService;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Contracts\Services\MailService;
use Modules\CMS\Contracts\Services\ProductService;
use Modules\CMS\Contracts\Services\SettingService;
use Modules\CMS\Contracts\Services\ShopFrequencyService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Contracts\Services\ShopService;
use Modules\CMS\Contracts\Services\SubscriberHistoryService;
use Modules\CMS\Services\AdminServiceImpl;
use Modules\CMS\Services\AuthServiceImpl;
use Modules\CMS\Services\BrandServiceImpl;
use Modules\CMS\Services\CategoryServiceImpl;
use Modules\CMS\Services\MailServiceImpl;
use Modules\CMS\Services\ProductServiceImpl;
use Modules\CMS\Services\SettingServiceImpl;
use Modules\CMS\Services\ShopFrequencyServiceImpl;
use Modules\CMS\Services\StorageServiceImpl;
use Modules\CMS\Services\ShopServiceImpl;
use Modules\CMS\Services\SubscriberHistoryServiceImpl;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    public array $bindings = [
        ProductService::class => ProductServiceImpl::class,
        SubscriberHistoryService::class => SubscriberHistoryServiceImpl::class,
        BrandService::class => BrandServiceImpl::class,
        CategoryService::class => CategoryServiceImpl::class,
        StorageService::class => StorageServiceImpl::class,
        AuthService::class => AuthServiceImpl::class,
        ShopService::class => ShopServiceImpl::class,
        AdminService::class => AdminServiceImpl::class,
        SettingService::class => SettingServiceImpl::class,
        ShopFrequencyService::class => ShopFrequencyServiceImpl::class,
        MailService::class => MailServiceImpl::class,
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
