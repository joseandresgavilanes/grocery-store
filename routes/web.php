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
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\StatsController;

// Ruta pública de bienvenida
Route::view('/', 'home')->name('home');

Route::middleware(['auth'])->group(function () {
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
     Route::get('orders/pending',[OrderController::class,'pending'])->name('orders.pending');
     Route::post('orders/{order}/complete',[OrderController::class,'complete'])->name('orders.complete');
     Route::post('orders/{order}/cancel',[OrderController::class,'cancel'])->name('orders.cancel');
     Route::resource('inventory',InventoryController::class)->only(['index','update','destroy']);
     Route::post('settings/shipping',[ShippingSettingsController::class,'update'])->name('settings.shipping.update');
     Route::get('stats/global',[StatsController::class,'global'])->name('stats.global');
   });


   Route::middleware(['auth','can:viewAny,App\Models\Product'])->get('inventory', [InventoryController::class,'index'])
   ->name('inventory.index');

// Ajustes de stock manual
Route::middleware(['auth','can:create,App\Models\StockAdjustment'])->post('inventory/{product}/adjust',
   [StockAdjustmentController::class,'store'])
   ->name('inventory.adjust');

// Órdenes de suministro
Route::middleware(['auth','can:viewAny,App\Models\SupplyOrder'])->group(function(){
  Route::get('supply-orders', [SupplyOrderController::class,'index'])
       ->name('supply-orders.index');
  Route::get('supply-orders/create', [SupplyOrderController::class,'create'])
       ->name('supply-orders.create');
  Route::post('supply-orders', [SupplyOrderController::class,'store'])
       ->name('supply-orders.store');
  // Auto-generar órdenes para productos bajo stock_lower_limit
  Route::post('supply-orders/auto', [SupplyOrderController::class,'autoGenerate'])
       ->name('supply-orders.auto');
  // Completar orden (actualiza stock)
  Route::post('supply-orders/{supplyOrder}/complete', [SupplyOrderController::class,'complete'])
       ->name('supply-orders.complete');
  // Eliminar orden
  Route::delete('supply-orders/{supplyOrder}', [SupplyOrderController::class,'destroy'])
       ->name('supply-orders.destroy');
});

Route::get('courses/showcase', [CourseController::class, 'showCase'])->name('courses.showcase');

Route::resource('courses', CourseController::class);

Route::resource('products', ProductController::class);

Route::resource('cards', CardController::class);

Route::resource('disciplines', DisciplineController::class);

Route::resource('departments', DepartmentController::class);

Route::get('cart', [CartController::class, 'show'])->name('cart.show');

// Añadir (o incrementar) items al carrito
Route::post('cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');

// Actualizar cantidad de un item
Route::patch('cart/{product}', [CartController::class, 'updateQuantity'])->name('cart.update');

// Eliminar un item
Route::delete('cart/{product}', [CartController::class, 'removeFromCart'])->name('cart.remove');

// Vaciar carrito completo
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/estadisticas', [StatsController::class, 'index'])->name('stats.index');
Route::get('/stats/export', [StatsController::class, 'export'])->name('stats.export');

require __DIR__ . '/auth.php';
