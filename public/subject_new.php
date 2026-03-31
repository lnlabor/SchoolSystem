<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\SubjectController;

$controller = new SubjectController();
$controller->create();
