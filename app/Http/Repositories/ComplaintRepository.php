<?php

namespace App\Http\Repositories;

use App\Models\Complaint;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Interfaces\ComplaintInterface;

class ComplaintRepository implements ComplaintInterface
{
    use ApiDesignTrait;

    private $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function allComplaints()
   {
       $complaints = $this->complaint::with('sender:id,name')->get();
       return $this->ApiResponse(200,'Complaints',null,$complaints);

   }
   
   public function getComplaint($request)
   {
       
    $validation = Validator::make($request->all(),[
        'complaint_id' => 'required|exists:complaints,id',
    ]);

    if($validation->fails()){
        return $this->apiResponse(422,'Error',$validation->errors());
    }
    $complaint = $this->complaint::with('sender')->find($request->complaint_id);

    return $this->apiResponse(200,'Complaint Data', null, $complaint);
 
   }

   public function deleteComplaint($request)
   {
    $validation = Validator::make($request->all(),[
        'complaint_id' => 'required|exists:complaints,id',
    ]);

    if($validation->fails()){
        return $this->apiResponse(422,'Error',$validation->errors());
    }
     $this->complaint::find($request->complaint_id)->delete();

    return $this->apiResponse(200,'Deleted');
   }

 

}
