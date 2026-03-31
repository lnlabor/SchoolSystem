<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\HomeController;

$controller = new HomeController();
$controller->index();
