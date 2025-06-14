<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupplyOrder;
use App\Models\StockAdjustment;
use App\Models\Category;
use App\Models\SettingsShippingCosts;
use App\Models\Settings;


use App\Policies\OrderPolicy;
use App\Policies\SupplyOrderPolicy;
use App\Policies\StockAdjustmentPolicy;
use App\Policies\UserPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ShippingCostPolicy;
use App\Policies\SettingsPolicy;
use App\Policies\CategoryPolicy;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
        // 1) Registrar las políticas en los modelos
        //
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(SupplyOrder::class, SupplyOrderPolicy::class);
        Gate::policy(StockAdjustment::class, StockAdjustmentPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Category::class,      CategoryPolicy::class);
        Gate::policy(SettingsShippingCosts::class,  ShippingCostPolicy::class);
        Gate::policy(Settings::class, SettingsPolicy::class);

        //
        // 2) Definir gates para los roles
        //
        Gate::define('member', fn(User $user) =>
            in_array($user->type, ['member', 'board'], true)
        );

        Gate::define('employee', fn(User $user) =>
            $user->type === 'employee'
        );

        Gate::define('board', fn(User $user) =>
            $user->type === 'board'
        );
    }
}