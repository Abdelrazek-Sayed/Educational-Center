<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiDesignTrait;
// use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class roles
{
    use ApiDesignTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {

        $userRole = auth()->user()->roleName->name;
        $allowedRoles = explode('.', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            return $this->ApiResponse(404, 'you do not have permission');
        }
        return $next($request);
    }
}
