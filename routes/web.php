<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShippingCostController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\SupplyOrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Ruta pública de bienvenida
Route::view('/', 'home')->name('home');

Route::middleware(['auth', 'notBlocked'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Dashboard protegido

// — Rutas para socios (member y board) —  
Route::middleware(['auth','can:member'])->group(function(){
     Route::post('cart/checkout', [CartController::class,'checkout'])->name('cart.checkout');
     Route::get('card', [CardController::class,'show'])->name('card.show');
     Route::post('card/topup',[CardController::class,'topup'])->name('card.topup');
     Route::get('orders/history',[OrderController::class,'history'])->name('orders.history');
     Route::get('stats/my',[StatsController::class,'myStats'])->name('stats.my');
   });
   
   // — Rutas para empleados —  
   Route::middleware(['auth','can:employee'])->group(function(){
     Route::get('orders/pending',[OrderController::class,'pending'])->name('orders.pending');
     Route::post('orders/{order}/complete',[OrderController::class,'complete'])->name('orders.complete');
     Route::resource('inventory',InventoryController::class)->only(['index','update','destroy']);
     Route::resource('supplies',SupplyOrderController::class)
           ->only(['index','store','update','destroy']);
   });
   
   // — Rutas para administradores (board) —  
   Route::middleware(['auth','can:board'])->group(function(){
     Route::resource('users',UserAdminController::class);
     Route::resource('categories',CategoryController::class);
     Route::resource('products',ProductController::class);
     Route::resource('settings',SettingsController::class)->only(['index','update']);
     Route::post('settings/shipping',[ShippingSettingsController::class,'update'])->name('settings.shipping.update');
     Route::get('stats/global',[StatsController::class,'global'])->name('stats.global');
   });

Route::get('courses/showcase', [CourseController::class, 'showCase'])->name('courses.showcase');

Route::resource('courses', CourseController::class);

Route::resource('products', ProductController::class);

Route::resource('cards', CardController::class);

Route::resource('disciplines', DisciplineController::class);

Route::resource('departments', DepartmentController::class);

Route::get('cart', [CartController::class, 'show'])->name('cart.show');
Route::post('cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');

require __DIR__ . '/auth.php';
