<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\ProgramController;

$controller = new ProgramController();
$controller->index();
