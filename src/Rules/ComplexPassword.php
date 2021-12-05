<?php

namespace AlfaDevTeam\AuthApi\Rules;

use Illuminate\Contracts\Validation\Rule;

class ComplexPassword implements Rule
{
    private $errorType;

    const LOWER_ERROR = 1;
    const UPPER_ERROR = 2;
    const NUMBER_ERROR = 3;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) < 8) {
            return false;
        }

        if (preg_match("/[A-Z]/", $value) === 0) { // || preg_match("/[А-Я]/", $value) === 0) {
            $this->setErrorType(self::UPPER_ERROR);
            return false;
        }

        if (preg_match("/[a-z]/", $value) === 0) { // || preg_match("/[а-я]/", $value) === 0) {
            $this->setErrorType(self::LOWER_ERROR);
            return false;
        }

        if (preg_match("!\d+!", $value) === 0) {
            $this->setErrorType(self::NUMBER_ERROR);
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
        switch ($this->errorType) {
            case self::LOWER_ERROR: {
                return 'lower error';
            }
            case self::UPPER_ERROR: {
                return 'upper error';
            }
            case self::NUMBER_ERROR: {
                return 'number error';
            }

            default: {
                return 'Check the new password for the rules described below';
            }
        }
    }

    protected function setErrorType(string $type)
    {
        $this->errorType = $type;
    }
}
