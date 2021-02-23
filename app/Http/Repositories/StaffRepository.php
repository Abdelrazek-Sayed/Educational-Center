<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Interfaces\StaffInterface;
use Illuminate\Support\Facades\Validator;

class StaffRepository implements StaffInterface
{

    use ApiDesignTrait;


    private $userModel;

    public function __construct(User $user)
    {

        $this->userModel = $user;
    }


    public function addStaff($request)
    {

        $validation = Validator::make($request->all(), [
            'name'  => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|min:8',
            'role_id'  => 'required|exists:roles,id',
        ]);


        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $this->userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response(200, 'staff was created');
    }

    public function allStaff()
    {
    }

    public function updateStaff($request)
    {
    }

    public function deleteStaff($request)
    {
    }
}
