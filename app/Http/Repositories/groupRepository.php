<?php

namespace  App\Http\Repositories;

use App\Http\Interfaces\groupInterface;
use App\Http\Traits\ApiDesignTrait;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class groupRepository implements groupInterface
{

    use ApiDesignTrait;

    private $groupModel;
    private $userModel;


    public function __construct(Group $group, User $user)
    {
        $this->groupModel = $group;
        $this->userModel = $user;
    }

    public function addGroup($request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'body' => 'required',
            'image' => 'required|image',
            'teacher_id' => 'required|exists:users,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error', $validation->errors());
        }

        $teacher = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where('is_teacher', 1);
        })->find($request->teacher_id);


        $path = Storage::putFile('groups', $request->file('image'));

        $this->groupModel::create([
            'name' => $request->name,
            'body' => $request->body,
            'image' => $path,
            'teacher_id' => $teacher->id,
            'created_by' => auth()->user()->id,         //dynamic

        ]);

        return $this->ApiResponse(200, 'group was created');
    }

    public function allGroup()
    {
        $groups = $this->groupModel::get();

        return $this->ApiResponse(200, 'Done', null, $groups);
    }

    public function updateGroup($request)
    {

        $validation = Validator::make($request->all(), [
            'name'  => 'required|min:3',
            'body'  => 'required',
            'image' => 'nullable|image',
            'teacher_id' => 'required|exists:users,id',
            'group_id'   => 'required|exists:groups,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error', $validation->errors());
        }

        $group =   $this->groupModel::find($request->group_id);
        $path = $group->image;

        if ($request->hasFile('image')) {

            Storage::delete($path);

            $path = Storage::putFile('groups', $request->file('image'));
        }


        $teacher_id = $this->userModel::whereHas('roleName', function ($query) {

            return $query->where('is_teacher', 1);
        })->find($request->teacher_id);

        if ($group) {

            $group->update([
                'name' => $request->name,
                'body' => $request->body,
                'image' => $path,
                'teacher_id' => $teacher_id->id,
                'craeted_by' => auth()->user()->id,         //dynamic
            ]);

            return $this->ApiResponse(200, 'group updated');
        }
        return $this->ApiResponse(422, 'not a group id');
    }

    public function deleteGroup($request)
    {
        $validation = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error ', $validation->errors());
        }

        $group = $this->groupModel::find($request->group_id);

        if ($group) {

            $path = $group->image;
            $group->delete();
            storage::delete($path);

            return $this->ApiResponse(200, 'group deleted');
        }
        return $this->ApiResponse(404, 'you did not  send a group id');
    }

    public function specificGroup($request)
    {
        $validation = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'validation error ', $validation->errors());
        }

        $group = $this->groupModel::find($request->group_id);

        if ($group) {

            return $this->ApiResponse(200, 'Done', null, $group);
        }
        return $this->ApiResponse(404, 'you did not  send a group id');
    }
}
