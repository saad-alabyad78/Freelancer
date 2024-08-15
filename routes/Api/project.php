<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Project\ProjectController;


Route::group([
    'middleware' => [
        'auth:sanctum' ,
        'verify_email' ,
        'role:freelancer,client' ,
    ] ,
    'prefix' => 'projects'
] , function(){

    Route::get('/' , [ProjectController::class , 'index']) ;
    Route::post('/{project}/client-ok' , [ProjectController::class , 'clientOk'] ) ;
    Route::post('/{project}/freelancer-ok' , [ProjectController::class , 'freelancerOk']) ;
    Route::put('{project}' , [ProjectController::class , 'update']) ;
    Route::delete('/{project}' , [ProjectController::class , 'delete']) ;

}) ;