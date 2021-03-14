<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiDesignTrait;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

// use Tymon\JWTAuth\Exceptions\TokenExpiredException;
// use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class jwtToken
{
    use ApiDesignTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            JWTAuth::parseToken()->authenticate();

        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {

                return $this->ApiResponse(422, 'Expired Token');

            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

                return $this->ApiResponse(422, 'Invalid Token');

            } else {

                return $this->ApiResponse(404, 'token not found');
            }

        }
        return $next($request);
    }
}
