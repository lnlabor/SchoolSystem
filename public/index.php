<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\SubjectController;
use App\Controllers\ProgramController;
use App\Controllers\UserController;
use App\Controllers\PasswordController;

$controllerName = strtolower(trim($_GET['controller'] ?? 'home'));
$actionName = strtolower(trim($_GET['action'] ?? 'index'));

$controllers = [
    'auth' => AuthController::class,
    'home' => HomeController::class,
    'subject' => SubjectController::class,
    'program' => ProgramController::class,
    'user' => UserController::class,
    'password' => PasswordController::class,
];

$actionMap = [
    'auth' => ['login' => 'showLogin', 'logout' => 'logout'],
    'home' => ['index' => 'index'],
    'subject' => ['list' => 'index', 'new' => 'create', 'edit' => 'edit'],
    'program' => ['list' => 'index', 'new' => 'create', 'edit' => 'edit'],
    'user' => ['list' => 'index', 'new' => 'create', 'edit' => 'edit'],
    'password' => ['change' => 'change'],
];

if (!isset($controllers[$controllerName])) {
    http_response_code(404);
    echo 'Not Found';
    exit;
}

$controllerClass = $controllers[$controllerName];
$controller = new $controllerClass();

$targetAction = $actionMap[$controllerName][$actionName] ?? null;
if (!$targetAction || !method_exists($controller, $targetAction)) {
    http_response_code(404);
    echo 'Not Found';
    exit;
}

$controller->$targetAction();
