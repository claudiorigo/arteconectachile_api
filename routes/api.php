<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductColorController;
use App\Http\Controllers\ProductSizeController;
use App\Http\Controllers\ProductColorSizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */

/* Route::group(['middleware' => 'api'], function($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/profile', [AuthController::class, 'profile']);
}); */

Route::group(['prefix' => 'users'], function($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'loginAdmin']);
    Route::post('/login_ecommerce', [AuthController::class, 'loginEcommerce']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/profile', [AuthController::class, 'profile']);
    //
    Route::group(['prefix' => 'admin'], function() {
        Route::get('/all', [UserController::class, 'index']);
        Route::post('/register', [UserController::class, 'store']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'categorias'], function($router) {
    Route::get('/listar', [CategorieController::class, 'index']);
    Route::post('/crear', [CategorieController::class, 'store']);
    Route::post('/update/{id}', [CategorieController::class, 'update']);
    Route::delete('/delete/{id}', [CategorieController::class, 'destroy']);
});

Route::group(['prefix' => 'productos'], function($router) {
    Route::get('/listar', [ProductController::class, 'index']);
    Route::get('/ver/{id}', [ProductController::class, 'show']);
    Route::post('/crear', [ProductController::class, 'store']);
    Route::post('/update/{id}', [ProductController::class, 'update']);

    Route::group(['prefix' => 'imagen'], function() {    
        Route::post('/agregar', [ProductImageController::class, 'store']);
        Route::delete('/eliminar/{id}', [ProductImageController::class, 'destroy']);
    });

    Route::group(['prefix' => 'inventario'], function() {                       //ProductColorSize
        Route::post('/agregar', [ProductColorSizeController::class, 'store']);
        Route::put('/actualizar/{id}', [ProductColorSizeController::class, 'update']);
        Route::delete('/eliminar/{id}', [ProductColorSizeController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'colores'], function($router) {                       //ProductColor
    Route::get('/listar', [ProductColorController::class, 'index']);
    /* Route::post('/crear', [CategorieController::class, 'store']);
    Route::post('/update/{id}', [CategorieController::class, 'update']);
    Route::delete('/delete/{id}', [CategorieController::class, 'destroy']); */
});

Route::group(['prefix' => 'medidas'], function($router) {                       //ProductSize
    /* Route::post('/crear', [CategorieController::class, 'store']);*/
    Route::get('/listar', [ProductSizeController::class, 'index']);
    Route::put('/actualizar/{id}', [ProductSizeController::class, 'update']);
    Route::delete('/eliminar/{id}', [ProductSizeController::class, 'destroy']);
    
});