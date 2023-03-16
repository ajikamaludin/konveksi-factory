<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ProductionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SizeController;
use App\Models\Color;
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
