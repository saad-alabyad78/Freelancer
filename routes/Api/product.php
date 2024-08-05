<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;

Route::prefix('products')->group(function(){

    Route::get('/' , [ProductController::class , 'index']) ;//
    Route::get('{product}/show' , [ProductController::class , 'show'] )->withTrashed() ;//

    //only freelancer
    Route::middleware(['auth:sanctum' , 'role:freelancer'])->group(function(){
        Route::post('/' , [ProductController::class , 'store']);//
        Route::put('/{product}' , [ProductController::class , 'update']);//
        Route::delete('{product}' , [ProductController::class , 'delete'])->withTrashed() ;//
    }) ;

    //only client
    Route::middleware(['auth:sanctum' , 'role:client'])->group(function(){
        Route::post('{product}/buy' , [ProductController::class , 'buyProduct']) ;
    }) ;

    //both
    Route::middleware(['auth:sanctum' , 'role:freelancer,client'])->group(function(){
        Route::get('/my-products' , [ProductController::class , 'myProducts']) ;
    }) ;

});