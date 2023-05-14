<?php
use Illuminate\Support\Facades\Route;

Route::any('ssl/callback/{status}', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('ssl.callback');
