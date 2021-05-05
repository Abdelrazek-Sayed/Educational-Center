<?php

namespace App\Http\Repositories;

use App\Models\Group;
use App\Http\Traits\ApiDesignTrait;
use App\Http\Interfaces\EndUserInterface;
use App\Models\StudentGroup;
use App\Models\User;

class EndUserRepository implements EndUserInterface
{
    use ApiDesignTrait;

    private $groupModel;
    private $user;

    public function  __construct(Group $groupModel, User $user)
    {
        $this->groupModel = $groupModel;
        $this->user = $user;
    }


    public function userGroups()
    {
        $userRole = auth()->user()->roleName->name;
        $userId = auth()->user()->id;
        if ($userRole == 'Teacher') {

            $data = $this->groupModel::where('teacher_id', $userId)->withCount('students')->get();
        } elseif ($userRole == 'Student') {
            $data = $this->groupModel::whereHas(
                'students',
                function ($query)  use ($userId) {
                    $query->where([['student_id', $userId], ['count', '>=', 1]]);
                }
            )->withCount('students')->get();
        }

        return $this->ApiResponse(200, 'Done', null, $data);
    }
}
