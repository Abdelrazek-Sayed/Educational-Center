<?php

namespace App\Rules;

use App\Models\Group;
use Illuminate\Contracts\Validation\Rule;

class ValidGroupId implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $values)
    {
        // $Group_ids = Group::pluck('id')->toArray();
        $Group_ids [] = Group::get();
        foreach ($values as $value) {

            if (!in_array($value[0], $Group_ids)) {
                return false;
            }

        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid group id';
    }
}
