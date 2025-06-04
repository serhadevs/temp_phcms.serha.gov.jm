<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileExtensions implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private $file_extension;
    public function __construct($file_extension)
    {
        $this->file_extension = $file_extension;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!in_array($this->file_extension, ['jpg', 'jpeg', 'png'])) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please upload an image with a valid file type. Accepted file types: .jpeg, .jpg, .png';
    }
}
