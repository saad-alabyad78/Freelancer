<?php
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MoneyTransferController;

Route::middleware(['auth:sanctum, verify_email, role:admin'])->group(function () {
    Route::post('/complaints', [ComplaintController::class, 'store'])->withoutMiddleware('role:admin');
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::post('/money-transfer', [MoneyTransferController::class, 'store']);
    Route::post('/users/{id}/freeze', [ComplaintController::class, 'freezeUser']);
});
