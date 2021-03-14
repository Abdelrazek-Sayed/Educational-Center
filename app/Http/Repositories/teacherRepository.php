<?php

namespace App\Http\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Interfaces\teacherInterface;
use Illuminate\Support\Facades\Validator;

// use App\Http\Repositories\teacherRepository;



class teacherRepository implements teacherInterface
{
    use ApiDesignTrait;


    private $userModel;
    private $roleModel;

    public function __construct(User $user, Role $role)
    {

        $this->userModel = $user;
        $this->roleModel = $role;
    }


    public function addTeacher($request)
    {

        $validation = Validator::make($request->all(), [

            'name'  => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|min:8',
            'role_id'  => 'required|exists:roles,id',
        ]);

        if ($validation->fails()) {

            return $this->ApiResponse(422, 'Validation error', $validation->errors());
        }

        $this->userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return $this->ApiResponse(200, 'teacher created');
    }

    public function allTeacher()
    {

        $teachers = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where('is_teacher', 1);
        })->get();

        return $this->ApiResponse(200, 'done', null, $teachers);
    }

    public function updateTeacher($request)
    {

        $validation = Validator::make($request->all(), [

            'name'  => 'required|min:3',
            'email' => [
                'required', 'email',
                Rule::unique('users')->ignore($request->teacher_id)
            ],

            'phone' => 'required|string',
            'password' => 'required|min:8',
            'role_id'  => 'required|exists:roles,id',
            'teacher_id'  => 'required|exists:users,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error', $validation->errors());
        }

        $teacher = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where('is_teacher', 1);
        })->find($request->teacher_id);

        if ($teacher) {

            $teacher->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);
            return $this->ApiResponse(200, 'teacher updated');
        }
        return $this->ApiResponse(422, 'not a teacher');
    }


    public function deleteTeacher($request)
    {
        $validation = validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error', $validation->errors());
        }

        $teacher = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where('is_teacher', 1);
        })->find($request->teacher_id);

        if ($teacher) {
            $teacher->delete();
            return $this->ApiResponse(200, 'teacher deleted');
        }
        return $this->ApiResponse(422, 'not a teacher');
    }

    public function specificTeacher($request)
    {
        $validation = validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error', $validation->errors());
        }

        $teacher = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where('is_teacher', 1);
        })->find($request->teacher_id);

        if ($teacher) {

            return $this->ApiResponse(200, 'Done' ,$teacher);
        }
        return $this->ApiResponse(422, 'not a teacher');
    }
}
