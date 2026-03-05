<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class NotOldPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the provided password matches the current one
        return !Hash::check($value, Auth::user()->password);
    }

    public function message()
    {
        return 'Your new password cannot be the same as your current password.';
    }
}
