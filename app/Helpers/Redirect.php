<?php

namespace App\Helpers;

/**
 * Redirect Helper Class
 * 
 * Provides convenient redirect methods
 */
class Redirect
{
    /**
     * Redirect to URL
     * 
     * @param string $url
     */
    public static function to(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Redirect to home page
     */
    public static function home(): void
    {
        self::to('/public/home.php');
    }

    /**
     * Redirect to login page
     */
    public static function login(): void
    {
        self::to('/public/login.php');
    }

    /**
     * Redirect to previous page (using referer)
     * 
     * @param string $default
     */
    public static function back(string $default = '/public/home.php'): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? $default;
        self::to($referer);
    }

    /**
     * Redirect with response code
     * 
     * @param string $url
     * @param int $code
     */
    public static function toWithCode(string $url, int $code = 302): void
    {
        http_response_code($code);
        header('Location: ' . $url);
        exit;
    }
}
