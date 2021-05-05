<?php

namespace App\Http\Repositories;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Traits\ApiDesignTrait;
use App\Http\Interfaces\AuthInterface;
use Illuminate\Support\Facades\Validator;

class AuthRepository implements AuthInterface
{

    use ApiDesignTrait;

    private $userModel; // property

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }
    //
    // public function login()
    // {
    //     // $req = Validator::make($request->all(), [
    //     //     'email' => 'required|email',
    //     //     'password' => 'required',
    //     // ]);

    //     // if ($req->fails()) {
    //     //     return $this->apiResponse(422, 'ValidatorErrors', $req->errors() );
    //     // }

    //     // $credentials = $request->only('email', 'password');
    //     $credentials = request(['email', 'password']);

    //     if ($token = JWTAuth::attempt($credentials)) {
    //         return $this->respondWithToken($token);
    //     }

    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    //
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->ApiResponse(401, 'unauthorized');
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $userData = $this->userModel::find(auth()->user()->id);
        $data = [
            'name' => $userData->name,
            'email' => $userData->email,
            'phone' => $userData->phone,
            'role_id' => $userData->role_id,
            'role' => auth()->user()->roleName->name,
            'access_token' => $token,
        ];
        return $this->ApiResponse(200, 'Done', null, $data);
    }

    public function updatePassword($request)
    {
        //     $validation = Validator::make($request->all(),[
        //         'old_password' => ['required' ,new MatchOldPassword],
        //         'new_password' => 'required|min:6',
        //     ]);
        //     if($validation->fails()){
        //         return $this->ApiResponse(422,'error',$validation->errors());
        //     }
        //     $user = auth()->user();

        //     $user->update([
        //         'password' => Hash::make($request->new_password),
        //     ]);
        //     return $this->ApiResponse(200,'password updated');

    }
}
