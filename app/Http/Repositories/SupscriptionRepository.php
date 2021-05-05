<?php

namespace App\Http\Repositories;

use App\Models\StudentGroup;
use App\Http\Traits\ApiDesignTrait;
use App\Http\Interfaces\SupscriptionInterface;

class SupscriptionRepository implements SupscriptionInterface
{
    use ApiDesignTrait;

    private $student_group;

    public function __construct(StudentGroup $student_group)
    {
        $this->student_group = $student_group;
    }

    public function LimitSupscriptions()
    {
       $limitSubscriptions = $this->student_group::whereIn('count',[1,2])
       ->with('')->get();
       return $this->ApiResponse(200,'data',null,$limitSubscriptions);
    }
    
    
    // public function LimitSupscriptions ()
    // {
    //     $limitSubscriptions = $this->groupStudent->whereIn('count', [1,2])
    //                                 ->with('student', 'group')->get();

    //     // return $this->apiResponse(200, 'Data', null, SubscriptionResource::collection($limitSubscriptions));
    // }

    public function ClosedSupscriptions()
    {
        $closedSubscriptions = $this->groupStudent->where('count', 0)
                                    ->with('student', 'group')->get();

        // return $this->apiResponse(200, 'Data', null, SubscriptionResource::collection($closedSubscriptions));
    }

   
}
