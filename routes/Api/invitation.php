<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;

// company
Route::group(
    [
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' ,
            'role:company' ,
        ],
    ],
    function(){
        Route::post('/invitations', [InvitationController::class, 'sendInvitation']);
        Route::delete('/invitations/{id}', [InvitationController::class, 'deleteInvitation']);
    });

// freelancer
Route::group(
    [
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' ,
            'role:freelancer' ,
        ],
    ],
    function(){
        Route::post('/invitations/{id}/accept', [InvitationController::class, 'acceptInvitation']);
        Route::post('/invitations/{id}/reject', [InvitationController::class, 'rejectInvitation']);
    });

// both
Route::group(
    [
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' ,
            'role:company,freelancer' ,
        ],
    ],
    function(){
        Route::get('/invitations', [InvitationController::class, 'getInvitations']);

    });
