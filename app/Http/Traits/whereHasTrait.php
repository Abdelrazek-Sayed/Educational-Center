<?php

namespace  App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Request;

trait WhereHasTrait
{

    // public function wherehas(User $userModel, Request $request, $user = null, $x, $user_id)
    // {
//         $user  = $userModel::whereHas('roleName', function ($query) {
//             return $query->where("$x", 1);
//         })->find($request->user_id);
//     }
}
