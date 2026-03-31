<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\SessionManager;
use App\Helpers\Redirect;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (Auth::isLoggedIn()) {
            Redirect::home();
        }

        $data = [
            'error' => '',
            'username' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $data['username'] = htmlspecialchars($username);

            if ($username === '' || $password === '') {
                $data['error'] = 'Username and password are required.';
            } elseif (Auth::login($username, $password)) {
                SessionManager::setSuccess('Login successful!');
                Redirect::home();
            } else {
                $data['error'] = 'Invalid username or password.';
            }
        }

        $this->render(__DIR__ . '/../Views/auth/login.php', $data);
    }

    public function logout(): void
    {
        Auth::requireLogin();
        Auth::logout();
        SessionManager::setSuccess('You have been logged out.');
        Redirect::login();
    }
}
