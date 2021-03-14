<?php

namespace App\Http\Traits;

use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ValidationFailsTrait;
use Illuminate\Support\Facades\Validator;

trait  addUserTrait

{

    use ApiDesignTrait, ValidationFailsTrait;

    public function addUser($request, $user)
    {

        $validation = Validator::make($request->all(), [
            'name'  => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|min:8',
            'role_id'  => 'required|exists:roles,id',
        ]);


        if ($validation->fails()) {

            return $this->error($validation);
        }

        $this->userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);
        return $this->ApiResponse(200, "user created");
    }
}
