<?php

namespace App\Http\Controllers;

use App\Classes\User as ClassesUser;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    //define properti class User
    protected $user;

    //konstruk Class User
    public function __construct(ClassesUser $user)
    {
        //assignment ke user (controller)
        $this->user = $user;
    }

//register
    public function register(RegisterRequest $request)
    {
        try {
            //get data dari inject konstruk
            $user = $this->user->register($request);

            return response()->json([
                'status' => true,
                'message' => "Berhasil Register",
                'data' => $user,
            ]);
        } catch (\Throwable $th) {
            $error = [
                'error' => $th->getMessage()
            ];
            return response()->json($error, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //login
    public function login(LoginRequest $request){
        try {
            //get data dari inject konstruk
            $token = $this->user->login($request);

            return $token;

        } catch (\Throwable $th) {
            $error = [
                'error' => $th->getMessage()
            ];
            return response()->json($error, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    //sendresetPassword
    public function sendResetPassword(Request $request){
        try {
            //validasi
            $request->validate([
                'email' => ['required', 'string', 'email']
            ]);

            //get data dari inject konstruk
            $response = $this->user->sendResetPassword($request);

            return $response;
        } catch (\Throwable $th) {
            $error = [
                'error' => $th->getMessage()
            ];
            return response()->json($error, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            //validasi
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => 'required',
                'password_validation' => 'required',
            ]);

            //get data dari inject konstruk
            $response = $this->user->resetPassword($request);

            return $response;
        } catch (\Throwable $th) {
            $error = [
                'error' => $th->getMessage()
            ];
            return response()->json($error, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request){
        try {
            //get data dari inject konstruk
            $logout = $this->user->logout($request);

            return $logout;
        } catch (\Throwable $th) {
            $error = [
                'error' => $th->getMessage()
            ];
            return response()->json($error, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
