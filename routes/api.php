<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('send-reset-password', 'sendResetPassword');
    Route::post('reset-password/{token}', 'resetPassword')->name('password.reset');
});

Route::group(["middleware" => ["auth:sanctum"]], function () {

    Route::get("dashboard", function (){
        return response()->json('berhasil redirect ke dashboard');
    })->name('dashboard');

    Route::get("logout", [AuthController::class, "logout"]);

});
