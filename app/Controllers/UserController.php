<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\SessionManager;
use App\Helpers\Redirect;
use App\Helpers\Validator;
use App\Models\User;

class UserController extends BaseController
{
    public function index(): void
    {
        Auth::requireAdmin();

        $userModel = new User();

        $this->render(__DIR__ . '/../Views/user/list.php', [
            'result' => $userModel->getAll()
        ]);
    }

    public function create(): void
    {
        Auth::requireAdmin();

        $errors = [];
        $username = '';
        $accountType = '';
        $roles = ['admin', 'staff', 'teacher', 'student'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            $accountType = trim($_POST['account_type'] ?? '');

            if (!Validator::required($username)) {
                $errors[] = 'Username is required.';
            } elseif (!Validator::length($username, 3, 50)) {
                $errors[] = 'Username must be between 3 and 50 characters.';
            }

            if (!Validator::required($password)) {
                $errors[] = 'Password is required.';
            } elseif (!Validator::passwordStrength($password, 6)) {
                $errors[] = 'Password must be at least 6 characters.';
            }

            if ($password !== $confirm) {
                $errors[] = 'Password confirmation does not match.';
            }

            if (!Validator::enum($accountType, $roles)) {
                $errors[] = 'Invalid account type selected.';
            }

            $userModel = new User();
            if ($userModel->usernameExists($username)) {
                $errors[] = 'Username already exists.';
            }

            if (empty($errors)) {
                if ($userModel->create($username, $password, $accountType)) {
                    $this->setSuccess('User created successfully!');
                    Redirect::to('users_list.php');
                } else {
                    $errors[] = 'Database error: could not create user.';
                }
            }
        }

        $this->render(__DIR__ . '/../Views/user/form.php', [
            'action' => 'Add',
            'errors' => $errors,
            'username' => $username,
            'accountType' => $accountType,
            'roles' => $roles,
            'back' => 'users_list.php'
        ]);
    }

    public function edit(): void
    {
        Auth::requireAdmin();

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $this->setError('Invalid user ID.');
            Redirect::to('users_list.php');
        }

        $userModel = new User();
        $result = $userModel->getById($id);

        if (!$result || $result->num_rows === 0) {
            $this->setError('User not found.');
            Redirect::to('users_list.php');
        }

        $userData = $result->fetch_assoc();
        $username = $userData['username'];
        $accountType = $userData['account_type'];
        $roles = ['admin', 'staff', 'teacher', 'student'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $accountType = trim($_POST['account_type'] ?? '');

            if (!Validator::required($username)) {
                $errors[] = 'Username is required.';
            } elseif (!Validator::length($username, 3, 50)) {
                $errors[] = 'Username must be between 3 and 50 characters.';
            }

            if (!Validator::enum($accountType, $roles)) {
                $errors[] = 'Invalid account type selected.';
            }

            if ($userModel->usernameExists($username, $id)) {
                $errors[] = 'Username already exists for another user.';
            }

            if (empty($errors)) {
                if ($userModel->update($id, $username, $accountType)) {
                    $this->setSuccess('User updated successfully!');
                    Redirect::to('users_list.php');
                } else {
                    $errors[] = 'Database error: could not update user.';
                }
            }
        }

        $this->render(__DIR__ . '/../Views/user/form.php', [
            'action' => 'Edit',
            'errors' => $errors,
            'username' => $username,
            'accountType' => $accountType,
            'roles' => $roles,
            'id' => $id,
            'back' => 'users_list.php'
        ]);
    }
}
