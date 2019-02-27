<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsCommitteeYearRule implements Rule
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
    public function passes($attribute, $value)
    {
        $allowedYears = [
            config('app.committee_year'),
            config('app.committee_year')-1
        ];

        return in_array( $value, $allowedYears );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The year must be within the last two years.';
    }
}
