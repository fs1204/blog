<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TokyoAddress implements Rule
{
    private $pref;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($pref)
    {
        $this->pref = $pref;
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
        if ($this->pref === '東京都' && blank($value)) {
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
        return '住所を書いてください。';
    }
}
