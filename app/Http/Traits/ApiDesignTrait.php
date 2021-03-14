<?php

namespace  App\Http\Traits;

trait ApiDesignTrait
{


    public function  ApiResponse($status = 200, $message = null, $errors = null, $data = null)
    {

        $array = [

            'status' => $status,
            'message' => $message,
        ];

        if (is_null($data) and !is_null($errors)) {

            $array['errors'] = $errors;
        } elseif (is_null($errors)  and !is_null($data)) {

            $array['data'] = $data;
        }

        return response($array, 200);
    }
}
