<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\CompositionController;
use App\Http\Controllers\Api\CuttingController;
use App\Http\Controllers\Api\FabricItemController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ProductionController;
use App\Http\Controllers\Api\RatioController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/roles', [RoleController::class, 'index'])->name('api.role.index');
Route::get('/brands', [BrandController::class, 'index'])->name('api.brand.index');
Route::get('/buyers', [BuyerController::class, 'index'])->name('api.buyer.index');
Route::get('/materials', [MaterialController::class, 'index'])->name('api.material.index');
Route::get('/colors', [ColorController::class, 'index'])->name('api.color.index');
Route::get('/sizes', [SizeController::class, 'index'])->name('api.size.index');
Route::get('/productions', [ProductionController::class, 'index'])->name('api.production.index');
Route::get('/suppliers', [SupplierController::class, 'index'])->name('api.suppliers.index');
Route::get('/fabric-item', [FabricItemController::class, 'index'])->name('api.fabric-item.index');
Route::get('/fabric', [FabricItemController::class, 'fabric'])->name('api.fabric.index');
Route::get('/ratios', [RatioController::class, 'index'])->name('api.ratios.index');
Route::get('/cuttings', [CuttingController::class, 'index'])->name('api.cutting.index');
Route::get('/compositions', [CompositionController::class, 'index'])->name('api.composition.index');
