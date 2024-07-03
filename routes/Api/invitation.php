<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;

// company
Route::middleware('auth:sanctum', 'verify_email', 'role:company')->group(function () {
    Route::post('/invitations', [InvitationController::class, 'sendInvitation']);
    Route::delete('/invitations/{id}', [InvitationController::class, 'deleteInvitation']);
});

// freelancer
Route::middleware('auth:sanctum', 'verify_email', 'role:freelancer')->group(function () {
    Route::post('/invitations/{id}/accept', [InvitationController::class, 'acceptInvitation']);
    Route::post('/invitations/{id}/reject', [InvitationController::class, 'rejectInvitation']);
});

// both
Route::middleware('auth:sanctum', 'verify_email', 'role:company,freelancer')->group(function () {
    Route::get('/invitations', [InvitationController::class, 'getInvitations']);
});
