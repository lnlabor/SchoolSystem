<?php

namespace App\Core;

/**
 * SessionManager Class
 * 
 * Handles all session operations including:
 * - Starting and destroying sessions
 * - Setting and getting session values
 * - Managing flash messages
 */
class SessionManager
{
    /**
     * Initialize session
     */
    public static function start(): void
    {
        if (session_status() === \PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Destroy session
     */
    public static function destroy(): void
    {
        if (session_status() !== \PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }

    /**
     * Get session value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set session value
     * 
     * @param string $key
     * @param mixed $value
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if session key exists
     * 
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session value
     * 
     * @param string $key
     */
    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Set flash error message
     * 
     * @param string $message
     */
    public static function setError(string $message): void
    {
        self::set('error', $message);
    }

    /**
     * Set flash success message
     * 
     * @param string $message
     */
    public static function setSuccess(string $message): void
    {
        self::set('success', $message);
    }

    /**
     * Get and clear flash messages
     * 
     * @return string
     */
    public static function getFlashMessages(): string
    {
        $output = '';

        if (self::has('error')) {
            $error = self::get('error');
            $output .= '<div class="error">' . htmlspecialchars($error) . '</div>';
            self::forget('error');
        }

        if (self::has('success')) {
            $success = self::get('success');
            $output .= '<div class="success">' . htmlspecialchars($success) . '</div>';
            self::forget('success');
        }

        return $output;
    }
}
