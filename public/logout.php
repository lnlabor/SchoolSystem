<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\AuthController;

$controller = new AuthController();
$controller->logout();
