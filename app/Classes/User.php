<?php

namespace App\Classes;

use App\Models\User as ModelsUser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class User
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register($data){

        //validasi inputan pw1 dan pw2
        if ($data-> password !== $data->password_validation){
            return response()->json([
                "status" => false,
                "message" => 'Password tidak sama',
            ]);
        }

        //create user
        $user = ModelsUser::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        return $user;
    }

    public function login($data){
        //get data user dari email
        $user = ModelsUser::where("email", $data->email)->first();

        if (!empty($user)){
            //validasi inputan pw dengan pw yg di db
            if (Hash::check($data->password, $user->password)){

                //cek email verificated?
                if (empty($user->email_verified_at)) {

                    //jika blm generate url verif email, return di response
                    $url_verifikasi = URL::signedRoute('email.verify', ['id' => $user->getKey()]);

                    return response()->json([
                        'status' => false,
                        'message' => 'Email belum diverifikasi',
                        'url_verifikasi' => $url_verifikasi,
                    ]);
                }

                //login
                $auth_user = auth()->login($user);

                //remember me handler
                if (!empty($data->remember_me) && $data->remember_me == true){
                    //jika true generate token dengan list remember, tanpa penambahan last used
                    // expire token sanctum set 30 hari
                    $token = $user->createToken("remember_token", ["remember"])->plainTextToken;

                    return response()->json([
                        "url_redirect" => route('dashboard'),
                        "user" => $auth_user
                    ], 200, ['Authorization' => "Bearer ". $token]); //return token di header
                }

                // jika tidak remember me maka return token last_used +29 hari, expired in 1 day
                $token = $user->createToken("ordinary_token", now()->addDays(29))->plainTextToken;
                return response()->json([
                    "url_redirect" => route('dashboard'),
                    "user" => $auth_user
                ], 200, ['Authorization' => "Bearer ". $token]);

            }else{
                return response()->json([
                    "status" => false,
                    "message" => 'Password Salah',
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "message" => 'email tidak ditemukan / belum terdaftar',
            ]);
        }
    }

    // Send Reset Password
    public function sendResetPassword($data)
    {
        //get user by email
        $user = ModelsUser::where("email", $data->email)->first();

        if (!empty($user)) {
            //create reset_token
            $token = Password::createToken($user);
            //generate url untuk reset token
            $resetPasswordUrl = URL::temporarySignedRoute('password.reset', now()->addHour(), ['token' => $token]);

            return response()->json([
                'status' => true,
                'url_reset_pw' => $resetPasswordUrl, //assign di response
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan',
            ]);
        }
    }

    // Reset Password
    public function resetPassword($data){
        //validasi jika pw tidak sama
        if ($data-> password !== $data->password_validation){
            return response()->json([
                "status" => false,
                "message" => 'Password tidak sama',
            ]);
        }

        //get user by email
        $user = ModelsUser::where('email' , $data->email)->first();
        if ($user) {
            // update pw
            $user->password = Hash::make($data->password);
            $user->save();

            return response()->json([
                "status" => true,
                "message" => 'Password berhasil direset',
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => 'Login Gagal',
            ]);
        }
    }

    // Logout
    public function logout($data){
        //get user auth
        $user = $data->user();

        //delete token
        $user->tokens()->delete();

        return response()->json([
            "status" => true,
            "message" => 'berhasil logout',
        ]);
    }
}
