<?php

namespace App\Http\Traits;

use App\Http\Traits\ApiDesignTrait;


trait ValidationFailsTrait
{

    use ApiDesignTrait;

    public function error($validation)
    {
        // if ($validation->fails()) {

        return $this->ApiResponse(422, 'validation error', $validation->errors());
        // }

    }
}
