<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShippingCostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\SupplyOrderController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CartController;


// Ruta pública de bienvenida
Route::view('/', 'home')->name('home');


Route::middleware(['auth', 'notBlocked'])->group(function () {
     Route::redirect('settings', 'settings/profile');

     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
     Volt::route('settings/password', 'settings.password')->name('settings.password');
     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
 });


// Dashboard protegido
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // CRUD de usuarios y tarjetas
    Route::resource('users',    UserController::class);

    // Ajuste de tarifa de membresía (único recurso)
    Route::get('settings/membership', [SettingController::class, 'edit'])
         ->name('settings.membership.edit');

    Route::put('settings/membership', [SettingController::class, 'update'])
         ->name('settings.membership.update');

    // Costes de envío
    Route::resource('shipping-costs', ShippingCostController::class)
         ->parameters(['shipping-costs' => 'shippingCost']);

    // Categorías y productos
    Route::resource('categories', CategoryController::class);
//     Route::resource('products',   ProductController::class);

    // Pedidos de clientes e ítems
    Route::resource('orders',      OrderController::class);
    Route::resource('order-items', OrderItemController::class)
         ->parameters(['order-items' => 'orderItem']);

    // Pedidos de reposición
    Route::resource('supply-orders', SupplyOrderController::class)
         ->parameters(['supply-orders' => 'supplyOrder']);

    // Ajustes de stock
    Route::resource('stock-adjustments', StockAdjustmentController::class)
         ->parameters(['stock-adjustments' => 'stockAdjustment']);

    // Transacciones
    Route::resource('transactions', TransactionController::class);
});


Route::get('courses/showcase', [CourseController::class, 'showCase'])->name('courses.showcase');

Route::resource('courses', CourseController::class);

Route::resource('products', ProductController::class);

Route::resource('cards',    CardController::class);

Route::resource('disciplines', DisciplineController::class);

Route::resource('departments', DepartmentController::class);

Route::get('cart', [CartController::class, 'show'])->name('cart.show');


require __DIR__.'/auth.php';
