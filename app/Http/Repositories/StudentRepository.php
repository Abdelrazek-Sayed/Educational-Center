<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StudentInterface;
use App\Http\Traits\addUserTrait;
use App\Http\Traits\ApiDesignTrait;
use App\Http\Traits\ValidationFailsTrait;
use App\Models\Group;
use App\Models\Role;
use App\Models\StudentGroup;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentRepository implements StudentInterface
{
    use ApiDesignTrait, ValidationFailsTrait, addUserTrait;

    private $userModel;
    private $roleModel;
    private $groupModel;
    private $student_group_Model;

    public function __construct(User $user, Role $Role, Group $group, StudentGroup $student_group)
    {

        $this->userModel = $user;
        $this->roleModel = $Role;
        $this->groupModel = $group;
        $this->student_group_Model = $student_group;
    }

    public function addStudent($request)
    {
        // group validation -- array method

        // $validation = Validator::make($request->all(), [
        //     'name' => 'required|min:3',
        //     'email' => 'required|email|unique:users',
        //     'phone' => 'required|min:10',
        //     'password' => 'required|min:5',
        //     // 'groups' => ['required', 'array', new GroupRule()],
        //     // 'groups' => 'required|array',
        //     'groups.*' => 'required',
        // ]);

        // if ($validation->fails()) {
        //     return $this->ApiResponse(422, " validation error", $validation->errors());
        // }

        // $groups = $request->groups;

        // foreach ($groups as $group) {

        //     if (!count($group) == 3) {
        //         return $this->ApiResponse(422, 'validation error ', 'reforamt the group data');
        //     }

        // }

        // for ($i = 0; $i < count($groups); $i++) {
        //     for ($j = $i + 1; $j < count($groups); $j++) {
        //         if ($groups[$i][0] == $groups[$j][0]) {
        //             return $this->ApiResponse(422, 'validatio error', 'group dublicated');
        //         }
        //     }

        //     $group_validation = Validator::make($group, [
        //         'groups' . $i . '.0' => 'exists:groups,id',
        //     ]);
        //     if ($group_validation->fails()) {
        //         return $this->ApiResponse(422, " validation error", $group_validation->errors());
        //     }
        // }

        // // index validation

        // // $student_role = $this->roleModel::where('name', 'student')->first();
        // // $student_role = $this->roleModel::where([['is_staff',0],['is_teacher',0]])->first();
        // $student_role = $this->roleModel::where('is_staff', 0)->where('is_teacher', 0)->first();

        // // user creation

        // $student = $this->userModel::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'phone' => $request->phone,
        //     'password' => Hash::make($request->password),
        //     'role_id' => $student_role->id,
        // ]);

        // // student group creation

        // for ($i = 0; $i < count($groups); $i++) {
        //     $this->student_group_Model::create([
        //         'student_id' => $student->id,
        //         'group_id' => $request->groups[$i][0],
        //         'count' => $request->groups[$i][1],
        //         'price' => $request->groups[$i][2],
        //     ]);
        // }

        // validation -- string method

        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10',
            'password' => 'required|min:5',
            'groups.*' => 'required',
        ]);

        $array = [];

        foreach ($request->groups as $group) {

            $groupAsArray = explode(',', $group);

            if (count($groupAsArray) != 3) {
                return $this->ApiResponse(422, 'validation error ', 'reforamt the group data');
            }

            if (in_array($groupAsArray[0], $array)) {
                return $this->ApiResponse(422, 'validation error ', 'group is dublicated');
            }
            $array[] = $groupAsArray[0];
        }

        if ($validation->fails()) {
            return $this->ApiResponse(422, "validation error", $validation->errors());
        }

        // creation in users model

        // $student_role = $this->roleModel::where([['is_teacher', 0], ['is_staff', 0]])->first();
        $student_role = $this->roleModel::where('name','student')->first();

        $student = $this->userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $student_role->id,
        ]);

        // student_group creation

        $this->student_group_Model::create([
            'group_id' => $groupAsArray[0],
            'student_id' => $student->id,
            'count' => $groupAsArray[1],
            'price' => $groupAsArray[2],
        ]);

        return $this->ApiResponse(200, "created", null, $student);
    }

    public function allStudents()
    {

        // $students  = $this->student_group_Model::get();
        $students = $this->userModel::whereHas('roleName', function ($query) {
            $query->where([['is_staff', 0], ['is_teacher', 0]]);
        })->withcount('studentGroups')->get();
        return $this->ApiResponse(200, 'done', null, $students);
    }

    public function updateStudent($request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $request->student_id,
            'phone' => 'required|min:10',
            'password' => 'required|min:5',
            'student_id' => 'required|exists:users,id',
            // 'groups' => ['required', 'array', new GroupRule()],
            'groups.*' => 'required|min:3',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, "validation error", $validation->errors());
        }

        $student = $this->userModel::find($request->student_id);

        if ($student) {

            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // array method

            if ($request->has('groups')) {

                $requestGroups = [];
                $allStudentGroups = $this->student_group_Model::where('student_id', $request->student_id)->pluck('group_id')->toArray();
                $groups = $request->groups;

                for ($i = 0; $i < count($groups); $i++) {
                    $requestGroups[] = $groups[$i][0];

                    $checkStudentGroup = $this->student_group_Model::where([['student_id', $request->student_id], ['group_id', $groups[$i][0]]])
                        ->first();
                    if ($checkStudentGroup) {
                        $checkStudentGroup->update([
                            'count' => $groups[$i][1],
                            'price' => $groups[$i][2],
                        ]);
                    } else {
                        $this->student_group_Model::create([
                            'student_id' => $request->student_id,
                            'group_id' => $groups[$i][0],
                            'count' => $groups[$i][1],
                            'price' => $groups[$i][2],
                        ]);
                    }
                }

                $oldGroups = array_diff($allStudentGroups, $requestGroups);
                foreach ($oldGroups as $oldGroup) {
                    $this->student_group_Model::where('group_id', $oldGroup)->where('student_id', $request->student_id)->delete();
                }

                // short method
                // $this->student_group_Model::whereNotIn('group_id', [$requestGroups])->where('student_id', $request->student_id)->delete();
            }

            // string method

            // if ($request->has('groups')) {
            //     foreach ($request->groups as $group) {
            //         $groupAsArray = explode(',', $group);
            //         $this->student_group_Model::create([
            //             'student_id' => $request->student_id,
            //             'group_id' => $groupAsArray[0],
            //             'count' => $groupAsArray[1],
            //             'price' => $groupAsArray[2],
            //         ]);
            //     }
            // }

            return $this->ApiResponse(200, 'student updated');
        }

        return $this->ApiResponse(404, 'not student_id');
    }

    public function deleteStudent($request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:student_groups,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error', $validation->errors());
        }

        $student = $this->student_group_Model::find($request->id);

        if ($student) {

            $student->delete();
            return $this->ApiResponse(200, 'deleted');
        }

        // return $this->ApiResponse(404,'not found *** ');

    }

    public function specificStudent($request)
    {
        $validation = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
        ]);

        if ($validation->fails()) {
            // return $this->ApiResponse(422, 'validation error', $validation->errors());
            return $this->error($validation);
        }

        // $student = $this->student_group_Model::find($request->id);
        $student = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where([['is_teacher', '!=', '1'], ['is_staff', '!=', '1']]);
        })->with('roleName')->find($request->student_id);

        if ($student) {
            return $this->ApiResponse(200, 'Done', null, $student);
        }

        return $this->ApiResponse(404, 'not found *** ');
    }

    public function updateStudentGroup($request)
    {
    }

    public function deleteStudentGroup($request)
    {
    }
}
