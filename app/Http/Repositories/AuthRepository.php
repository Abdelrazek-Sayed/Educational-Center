<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Traits\ApiDesignTrait;
use App\Http\Interfaces\AuthInterface;

class AuthRepository implements AuthInterface
{


    use ApiDesignTrait;

    private $userModel;    // property

    public function __construct(User $user) // object
    {
        $this->userModel = $user;
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {

            return $this->ApiResponse(401, 'unauthorized');
        }

        return $this->respondWithToken($token);
    }



    protected function respondWithToken($token)
    {

        // $userData = $this->userModel::find(auth()->user()->id);
        $userData = User::find(auth()->user()->id);

        $userRole = auth()->user()->roleName->name;

        $data = [
            'name'         => $userData->name,
            'email'        => $userData->email,
            'phone'        => $userData->phone,
            'role_id'      => $userData->role_id,
            'role'         => $userRole,
            'access_token' => $token,

        ];
        return $this->ApiResponse(200, 'Done', null, $data);
    }



}
