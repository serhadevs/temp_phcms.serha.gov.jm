<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ApplicationDateAfterExamDate implements Rule
{
    private $examDate;

    public function __construct($examDate)
    {
        $this->examDate = $examDate;
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
        return strtotime($value) < strtotime($this->examDate);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The application date must be before the exam date or not equal to the exam date.';
    }
}
