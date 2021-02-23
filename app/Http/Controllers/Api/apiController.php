<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Traits\ApiDesignTrait;
use App\Http\Controllers\Controller;

class apiController extends Controller
{
    use ApiDesignTrait;


    public function testApi($name)
    {
        if ($name == 'mohammed') {

            return $this->ApiResponse(200, 'Done', null, $name);
        } else {
            return $this->ApiResponse(422, 'Validation Error', 'name must be mohammed');
        }
    }
}
