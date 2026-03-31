<?php

namespace App\Helpers;

/**
 * Validator Helper Class
 * 
 * Provides validation utilities for form input
 */
class Validator
{
    /**
     * Validate required field
     * 
     * @param string $value
     * @return bool
     */
    public static function required(string $value): bool
    {
        return !empty(trim($value));
    }

    /**
     * Validate string length
     * 
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function length(string $value, int $min = 1, int $max = 255): bool
    {
        $length = strlen($value);
        return $length >= $min && $length <= $max;
    }

    /**
     * Validate email format
     * 
     * @param string $email
     * @return bool
     */
    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate numeric value
     * 
     * @param mixed $value
     * @return bool
     */
    public static function numeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    /**
     * Validate password strength
     * 
     * @param string $password
     * @param int $minLength
     * @return bool
     */
    public static function passwordStrength(string $password, int $minLength = 6): bool
    {
        return strlen($password) >= $minLength;
    }

    /**
     * Sanitize string input
     * 
     * @param string $input
     * @return string
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate enum value
     * 
     * @param string $value
     * @param array $allowedValues
     * @return bool
     */
    public static function enum(string $value, array $allowedValues): bool
    {
        return in_array($value, $allowedValues, true);
    }
}
