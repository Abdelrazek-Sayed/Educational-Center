<?php

namespace App\Http\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Interfaces\StaffInterface;
use Illuminate\Support\Facades\Validator;

class StaffRepository implements StaffInterface
{

    use ApiDesignTrait;


    private $userModel;
    private $roleModel;

    public function __construct(User $user, Role $role)
    {

        $this->userModel = $user;
        $this->roleModel = $role;
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

        return response()->json([

            200, 'staff was created'
        ]);
    }

    public function allStaff()
    {
        $is_staff = 1;

        $staff = $this->userModel::whereHas(
            'roleName',
            function ($query) use ($is_staff) {
                return $query->where('is_staff', $is_staff);
            }
        )->with('roleName')->get();

        return $this->ApiResponse(200, "done", null, $staff);
    }

    public function updateStaff($request)
    {

        $validation = Validator::make($request->all(), [

            'name'  => 'required|min:3',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->staff_id)
            ],

            // 'email' => 'required|email|unique:users,email,'.$request->staff_id,
            'phone' => 'required|string',
            'password' => 'required|min:8',

            'role_id'  => 'required|exists:roles,id',
            'staff_id' => 'required|exists:users,id',
        ]);

        //  make it  as  trait
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $staff  = $this->userModel::whereHas('roleName', function ($query) {
            return $query->where('is_staff', 1);
        })->find($request->staff_id);

        if ($staff) {

            $staff->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);


            return $this->ApiResponse(200, 'staff member updated');
        }

        return $this->ApiResponse(404, "not staff member");
    }



    public function deleteStaff($request)
    {
        $validation = Validator::make($request->all(), [
            'staff_id' => 'required|exists:users,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, "validation Error", $validation->errors());
        }

        $staff = $this->userModel::whereHas(
            'roleName',
            function ($query) {
                return $query->where('is_staff', 1);
            }
        )->find($request->staff_id);

        if ($staff) {

            $staff->delete();
            return $this->ApiResponse(200, "staff deleted");
        }
        return $this->ApiResponse(422, "not staff");
    }


    public function specificStaff($request)
    {
        // validation
        $validation = Validator::make($request->all(), [
            'staff_id' => 'required|exists:users,id',
        ]);

        if ($validation->fails()) {

            return $this->ApiResponse(422, "validation Error ", $validation->errors());
        }

        $staff = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where('is_staff', 1);
        })->find($request->staff_id);

        if ($staff) {

            return $this->ApiResponse(200, 'staff member', null, $staff);
        }
        return $this->ApiResponse(422, 'not staff member');
    }
}
