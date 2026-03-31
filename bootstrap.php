<?php

/**
 * Bootstrap File
 * 
 * Initializes the application:
 * - Autoloader setup
 * - Session initialization
 * - Core bootstrapping
 */

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/';

    // Does the class use the namespace prefix?
    $len = strlen('App\\');
    if (strncmp('App\\', $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize session
\App\Core\SessionManager::start();
