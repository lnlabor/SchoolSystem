<?php
/**
 * Autoloader for Classes
 */
function autoloadClasses($class) {
    $classPath = __DIR__ . '/classes/' . $class . '.php';
    if (file_exists($classPath)) {
        require_once $classPath;
    }
}

spl_autoload_register('autoloadClasses');

// Start session
Auth::startSession();
?>
