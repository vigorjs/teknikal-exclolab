<?php

use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/email/verify/{id}', function ($id) {
    $user = User::find($id);

    if ($user) {

        $user->email_verified_at = now();
        $user->save();

        return redirect('/email-verified')->with('success', 'Email berhasil diverifikasi!');
    } else {
        return redirect('/gagal-verifikasi-email')->with('error', 'Gagal memverifikasi email!');
    }
})->name('email.verify');
