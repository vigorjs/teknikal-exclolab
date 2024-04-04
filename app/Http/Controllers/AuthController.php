<?php

namespace App\Http\Controllers;

use App\Classes\User as ClassesUser;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    protected $user;

    public function __construct(ClassesUser $user)
    {
        $this->user = $user;
    }

    public function register(RegisterRequest $request)
    {
        try {

            $user = $this->user->register($request);

            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['name'] =  $user->name;

            return response()->json([
                'data' => $user,
                'access_token' => $success,
                'token_type' => 'Bearer'
            ]);
        } catch (\Throwable $th) {
            $error = [
                'error' => $th->getMessage()
            ];
            return response()->json($error, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request){
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // index query

    }
}
