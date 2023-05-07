<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\LineSewingController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCuttingController;
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
    Route::get('/productions/{production}/export', [ProductionController::class, 'export'])->name('production.export');
    Route::delete('/productions/{production}', [ProductionController::class, 'destroy'])->name('production.destroy');

    // line-sewing
    Route::get('/line/sewing', [LineSewingController::class, 'index'])->name('line.sewing.index');
    Route::post('/line/sewing/{item}', [LineSewingController::class, 'store'])->name('line.sewing.create');

    // supplier
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    //fabric
    Route::get('/fabrics', [FabricController::class, 'index'])->name('fabric.index');
    Route::get('/fabrics/create', [FabricController::class, 'create'])->name('fabric.create');
    Route::post('/fabrics', [FabricController::class, 'store'])->name('fabric.store');
    Route::get('/fabrics/{fabric}/edit', [FabricController::class, 'edit'])->name('fabric.edit');
    Route::put('/fabrics/{fabric}', [FabricController::class, 'update'])->name('fabric.update');
    Route::delete('/fabrics/{fabric}', [FabricController::class, 'delete'])->name('fabric.destroy');

    //ration
    Route::get('/ratios', [RatioController::class, 'index'])->name('ratio.index');
    Route::get('/ratios/create', [RatioController::class, 'create'])->name('ratio.create');
    Route::post('/ratios', [RatioController::class, 'store'])->name('ratio.store');
    Route::get('/ratios/{ratio}/edit', [RatioController::class, 'edit'])->name('ratio.edit');
    Route::put('/ratios/{ratio}', [RatioController::class, 'update'])->name('ratio.update');
    Route::delete('/ratios/{ratio}', [RatioController::class, 'destroy'])->name('ratio.destroy');

    //cutting
    Route::get('/cuttings', [CuttingController::class, 'index'])->name('cutting.index');
    Route::get('/cuttings/create', [CuttingController::class, 'create'])->name('cutting.create');
    Route::post('/cuttings', [CuttingController::class, 'store'])->name('cutting.store');
    Route::get('/cuttings/{cutting}/edit', [CuttingController::class, 'edit'])->name('cutting.edit');
    Route::put('/cuttings/{cutting}', [CuttingController::class, 'update'])->name('cutting.update');
    Route::delete('/cuttings/{cutting}', [CuttingController::class, 'destroy'])->name('cutting.destroy');
    Route::get('/cuttings/{cutting}/export', [CuttingController::class, 'export'])->name('cutting.export');

    // User Cutting
    Route::get('/user-cuttings', [UserCuttingController::class, 'index'])->name('user-cutting.index');
    Route::post('/user-cuttings', [UserCuttingController::class, 'store'])->name('user-cutting.store');

    //Setting Payroll
    Route::get('/settings', [PayrollController::class, 'index'])->name('setting.index');
    Route::put('/settings/{settingPayroll}', [PayrollController::class, 'store'])->name('setting.create');
    // Tv
    Route::get('/tv', [TvController::class, 'index'])->name('tv.index');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
