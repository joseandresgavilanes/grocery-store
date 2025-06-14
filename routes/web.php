<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\{
    CartController,
    CardController,
    OrderController,
    InventoryController,
    StockAdjustmentController,
    SupplyOrderController,
    UserController,
    CategoryController,
    ProductController,
    SettingController,
    ShippingCostController,
    StatsController,
    CourseController,
    DisciplineController,
    DepartmentController
};
use App\Models\{Order, Product, SupplyOrder, StockAdjustment, User};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::view('/', 'home')->name('home');

// Catálogo público
Route::get('products',               [ProductController::class,'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class,'show'])
     ->whereNumber('product')
     ->name('products.show');


// Carrito público (mostrar)
Route::get('cart',                   [CartController::class,'show'])->name('cart.show');

//llevar a la vista payment
Route::get('payment', function () {
    return view('cart.payment');
})->middleware('auth')->name('payment');

Route::get('edit', function () {
    return view('profile.edit');
})->middleware('auth')->name('edit');


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

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Protected Routes (auth + notBlocked)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function(){

    // Ajustes de perfil / contraseña / apariencia
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile',    'settings.profile')->name('settings.profile');
    Volt::route('settings/password',   'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    /*
    |--------------------------------------------------------------------------
    | Member & Board Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:member')->group(function(){
        Route::post('cart/checkout',    [CartController::class,'checkout'])->name('cart.checkout');
        Route::get('card',              [CardController::class,'show'])->name('card.show');
        Route::post('card/topup',       [CardController::class,'topup'])->name('card.topup');
        Route::get('orders/history',    [OrderController::class,'history'])->name('orders.history');
        Route::get('stats/my',          [StatsController::class,'myStats'])->name('stats.my');
        Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    });

    /*
    |--------------------------------------------------------------------------
    | Order Handling (Employee & Board)
    |--------------------------------------------------------------------------
    */
    // Ver pendientes
    Route::middleware('can:viewAny,'.Order::class)
         ->get('orders/pending', [OrderController::class,'pending'])
         ->name('orders.pending');

    // Completar (solo employee, policy::complete)
    Route::middleware('can:complete,order')
         ->post('orders/{order}/complete', [OrderController::class,'complete'])
         ->name('orders.complete');

    // Cancelar (solo board, policy::cancel)
    Route::middleware('can:cancel,order')
         ->post('orders/{order}/cancel', [OrderController::class,'cancel'])
         ->name('orders.cancel');


    /*
    |--------------------------------------------------------------------------
    | Inventory Management (Employee & Board)
    |--------------------------------------------------------------------------
    */
    // Listar e filtrar inventario
    Route::middleware('can:viewAny,'.Product::class)
         ->get('inventory', [InventoryController::class,'index'])
         ->name('inventory.index');

    // Ajuste manual de stock
    Route::middleware('can:create,'.StockAdjustment::class)
         ->post('inventory/{product}/adjust', [StockAdjustmentController::class,'store'])
         ->name('inventory.adjust');


    /*
    |--------------------------------------------------------------------------
    | Supply Orders (Employee & Board)
    |--------------------------------------------------------------------------
    */
    // CRUD básico y auto-generación
    Route::middleware('can:viewAny,'.SupplyOrder::class)->group(function(){
        Route::get('supply-orders',            [SupplyOrderController::class,'index'])->name('supply-orders.index');
        Route::get('supply-orders/create',     [SupplyOrderController::class,'create'])->name('supply-orders.create');
        Route::post('supply-orders',           [SupplyOrderController::class,'store'])->name('supply-orders.store');
        Route::post('supply-orders/auto',      [SupplyOrderController::class,'autoGenerate'])->name('supply-orders.auto');
    });

    // Completar supply order (employee & board)
    Route::middleware('can:complete,supplyOrder')
         ->post('supply-orders/{supplyOrder}/complete', [SupplyOrderController::class,'complete'])
         ->name('supply-orders.complete');

    // Eliminar supply order (solo board)
    Route::middleware('can:delete,supplyOrder')
         ->delete('supply-orders/{supplyOrder}', [SupplyOrderController::class,'destroy'])
         ->name('supply-orders.destroy');


    /*
    |--------------------------------------------------------------------------
    | Administration (Board Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:board')->group(function(){
        // Gestión de usuarios
        Route::resource('users', UserController::class)->parameters(['users'=>'user']);
        Route::post('users/{user}/block',   [UserController::class,'block'])->name('users.block');
        Route::post('users/{user}/unblock', [UserController::class,'unblock'])->name('users.unblock');
        Route::post('users/{user}/promote', [UserController::class,'promote'])->name('users.promote');
        Route::post('users/{user}/demote',  [UserController::class,'demote'])->name('users.demote');

        // Ajustes de negocio
        Route::resource('categories', CategoryController::class);
        // Gestión de productos (solo CRUD panel)
        Route::resource('products', ProductController::class)->except(['index','show']);
        Route::get('products/admin', [ProductController::class,'adminIndex'])->middleware('can:viewAny,'.Product::class)->name('products.admin');
        // Ajustes de membresía y envío
        Route::resource('settings', SettingController::class)->only(['index','update']);
        Route::post('settings/shipping', [ShippingCostController::class,'update'])
             ->name('settings.shipping.update');

        // Estadísticas globales
        Route::get('stats/global', [StatsController::class,'global'])->name('stats.global');
    });
});
