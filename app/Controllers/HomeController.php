<?php

namespace App\Controllers;

use App\Core\Auth;

class HomeController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();

        $this->render(__DIR__ . '/../Views/home.php', [
            'user' => Auth::currentUser(),
        ]);
    }
}
