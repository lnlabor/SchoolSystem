<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\SessionManager;
use App\Helpers\Redirect;
use App\Helpers\Validator;
use App\Models\Program;

class ProgramController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();

        $program = new Program();

        $this->render(__DIR__ . '/../Views/program/list.php', [
            'result' => $program->getAll(),
            'user' => Auth::currentUser(),
            'canEdit' => Auth::hasRole(['admin', 'staff'])
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(['admin', 'staff']);

        $errors = [];
        $code = '';
        $title = '';
        $years = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $years = trim($_POST['years'] ?? '');

            if (!Validator::required($code)) {
                $errors[] = 'Code is required.';
            }
            if (!Validator::required($title)) {
                $errors[] = 'Title is required.';
            }
            if (!Validator::numeric($years) || !Program::validateYears($years)) {
                $errors[] = 'Years must be a number between 1 and 6.';
            }

            if (empty($errors)) {
                $program = new Program();
                if ($program->create($code, $title, intval($years))) {
                    SessionManager::setSuccess('Program created successfully!');
                    Redirect::to('program_list.php');
                } else {
                    $errors[] = 'Database error: could not save program.';
                }
            }
        }

        $this->render(__DIR__ . '/../Views/program/form.php', [
            'action' => 'Add',
            'errors' => $errors,
            'code' => $code,
            'title' => $title,
            'years' => $years,
            'back' => 'program_list.php'
        ]);
    }

    public function edit(): void
    {
        Auth::requireRole(['admin', 'staff']);

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            SessionManager::setError('Invalid program ID.');
            Redirect::to('program_list.php');
        }

        $programModel = new Program();
        $result = $programModel->getById($id);

        if (!$result || $result->num_rows === 0) {
            SessionManager::setError('Program not found.');
            Redirect::to('program_list.php');
        }

        $program = $result->fetch_assoc();
        $code = $program['code'];
        $title = $program['title'];
        $years = $program['years'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $years = trim($_POST['years'] ?? '');

            if (!Validator::required($code)) {
                $errors[] = 'Code is required.';
            }
            if (!Validator::required($title)) {
                $errors[] = 'Title is required.';
            }
            if (!Validator::numeric($years) || !Program::validateYears($years)) {
                $errors[] = 'Years must be a number between 1 and 6.';
            }

            if (empty($errors)) {
                if ($programModel->update($id, $code, $title, intval($years))) {
                    SessionManager::setSuccess('Program updated successfully!');
                    Redirect::to('program_list.php');
                } else {
                    $errors[] = 'Database error: could not update program.';
                }
            }
        }

        $this->render(__DIR__ . '/../Views/program/form.php', [
            'action' => 'Edit',
            'errors' => $errors,
            'code' => $code,
            'title' => $title,
            'years' => $years,
            'id' => $id,
            'back' => 'program_list.php'
        ]);
    }
}
