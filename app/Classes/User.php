<?php

namespace App\Classes;

use App\Models\User as ModelsUser;

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
        $user = ModelsUser::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);

        return $user;
    }
}
