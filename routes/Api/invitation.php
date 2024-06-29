<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/invitations', [InvitationController::class, 'sendInvitation']);
    Route::get('/invitations', [InvitationController::class, 'getInvitations']);
    Route::delete('/invitations/{id}', [InvitationController::class, 'deleteInvitation']);
    Route::post('/invitations/{id}/accept', [InvitationController::class, 'acceptInvitation']);
    Route::post('/invitations/{id}/reject', [InvitationController::class, 'rejectInvitation']);
});
