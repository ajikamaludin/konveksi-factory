<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [GeneralController::class, 'index'])->name('dashboard');
    Route::get('/maintance', [GeneralController::class, 'maintance'])->name('maintance');

    // Size
    Route::get('/sizes', [SizeController::class, 'index'])->name('size.index');
    Route::post('/sizes', [SizeController::class, 'store'])->name('size.store');
    Route::put('/sizes/{size}', [SizeController::class, 'update'])->name('size.update');
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('size.destroy');

    // Color
    Route::get('/colors', [ColorController::class, 'index'])->name('color.index');
    Route::post('/colors', [ColorController::class, 'store'])->name('color.store');
    Route::put('/colors/{color}', [ColorController::class, 'update'])->name('color.update');
    Route::delete('/colors/{color}', [ColorController::class, 'destroy'])->name('color.destroy');

    // Material
    Route::get('/materials', [MaterialController::class, 'index'])->name('material.index');
    Route::post('/materials', [MaterialController::class, 'store'])->name('material.store');
    Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('material.destroy');

    // Brand
    Route::get('/brands', [BrandController::class, 'index'])->name('brand.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brand.store');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');
    
    // Brand
    Route::get('/buyers', [BuyerController::class, 'index'])->name('buyer.index');
    Route::post('/buyers', [BuyerController::class, 'store'])->name('buyer.store');
    Route::put('/buyers/{buyer}', [BuyerController::class, 'update'])->name('buyer.update');
    Route::delete('/buyers/{buyer}', [BuyerController::class, 'destroy'])->name('buyer.destroy');

    // User
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // Role
    Route::resource('/roles', RoleController::class);

    // Production
    Route::get('/productions', [ProductionController::class, 'index'])->name('production.index');
    Route::get('/productions/create', [ProductionController::class, 'create'])->name('production.create');
    Route::post('/productions', [ProductionController::class, 'store'])->name('production.store');
    Route::get('/productions/{production}/edit', [ProductionController::class, 'edit'])->name('production.edit');
    Route::put('/productions/{production}', [ProductionController::class, 'update'])->name('production.update');
    Route::delete('/productions/{production}', [ProductionController::class, 'destroy'])->name('production.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
