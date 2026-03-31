<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\SessionManager;
use App\Helpers\Redirect;
use App\Helpers\Validator;
use App\Models\User;

class PasswordController extends BaseController
{
    public function change(): void
    {
        Auth::requireLogin();

        $errors = [];
        $current = '';
        $new = '';
        $confirm = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current'] ?? '';
            $new = $_POST['new'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            if (!Validator::required($current) || !Validator::required($new) || !Validator::required($confirm)) {
                $errors[] = 'All fields are required.';
            }

            if (!Validator::passwordStrength($new, 6)) {
                $errors[] = 'New password must be at least 6 characters.';
            }

            if ($new !== $confirm) {
                $errors[] = 'Password confirmation does not match.';
            }

            if (empty($errors)) {
                $userId = Auth::currentUser()['id'];
                $userModel = new User();

                if (!$userModel->verifyPassword($userId, $current)) {
                    $errors[] = 'Current password is incorrect.';
                } else {
                    if ($userModel->changePassword($userId, $new)) {
                        SessionManager::setSuccess('Password updated successfully.');
                        Redirect::to('index.php?controller=home&action=index');
                    } else {
                        $errors[] = 'Database error: could not update password.';
                    }
                }
            }
        }

        $this->render(__DIR__ . '/../Views/password/change.php', [
            'errors' => $errors,
            'current' => $current,
            'new' => $new,
            'confirm' => $confirm
        ]);
    }
}
