<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\SessionInterface;
use App\Http\Traits\ApiDesignTrait;
use App\Models\Group;
use App\Models\Group_session;
use App\Models\StudentGroup;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Boolean;

class SessionRepository implements SessionInterface
{
    use ApiDesignTrait;

    private $group;
    private $group_session;

    public function __construct(Group $group, Group_session $group_session)
    {
        $this->group = $group;
        $this->group_session = $group_session;
    }

    public function allSessions()
    {
        $sessions = $this->group_session::with('groupSesssion:id,name')->get();
        return $this->ApiResponse(200, 'All Sessions', null, $sessions);
    }

    public function addSession($request)
    {
        // validation
        $validation = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
            'name'     => 'required|min:3',
            'link'     => 'required|url',
            'from'     => 'required|date_format:H:i',
            // 'from'     => 'required|date',
            // 'to'       => 'required|date',
            'to'       => 'required|date_format:H:i|after:from',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $this->group_session->create([
            'name' => $request->name,
            'link' => $request->link,
            'from' => $request->from,
            'to' => $request->to,
            'group_id' => $request->name,
        ]);

        StudentGroup::where('group_id', $request->group_id)->decrement('count', 1);
        return $this->ApiResponse(200, 'session created');
    }

    public function deleteSession($request)
    {
        $validation = Validator::make($request->all(), [
            'session_id' => 'required|exists:group_sessions',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation error', $validation->errors());
        }
        $session = $this->group_session::find($request->session_id);
        //
        if(! $this->availableTimeToDeleteSession($session)){
            return $this->ApiResponse(422,'can\'t be deleted');
        }
        //
        $session->delete();
        return $this->ApiResponse(200,'session deleted');
    }

    private function availableTimeToDeleteSession($session)
    {
        $currentDateTime = now();
        $currentTime = $currentDateTime->format('H:i');
        $currentDate = $currentDateTime->format('Y-m-d');

        $sessionDate = $session->created_at->format('Y-m-d');

        /* Validate that time is available to delete session*/
        if( $currentDate == $sessionDate && $currentTime >= $session->from  && $currentTime <= $session->to){
            return false;
        }
        return true;
    }

}
