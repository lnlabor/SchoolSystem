<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\SessionManager;
use App\Helpers\Redirect;
use App\Helpers\Validator;
use App\Models\Subject;

class SubjectController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();

        $subject = new Subject();

        $this->render(__DIR__ . '/../Views/subject/list.php', [
            'result' => $subject->getAll(),
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
        $unit = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $unit = trim($_POST['unit'] ?? '');

            if (!Validator::required($code)) {
                $errors[] = 'Code is required.';
            }
            if (!Validator::required($title)) {
                $errors[] = 'Title is required.';
            }
            if (!Validator::numeric($unit) || floatval($unit) <= 0 || !Subject::validateUnit($unit)) {
                $errors[] = 'Unit must be a number between 0.5 and 10.';
            }

            if (empty($errors)) {
                $subject = new Subject();
                if ($subject->create($code, $title, floatval($unit))) {
                    SessionManager::setSuccess('Subject created successfully!');
                    Redirect::to('subject_list.php');
                } else {
                    $errors[] = 'Database error: could not save subject.';
                }
            }
        }

        $this->render(__DIR__ . '/../Views/subject/form.php', [
            'action' => 'Add',
            'errors' => $errors,
            'code' => $code,
            'title' => $title,
            'unit' => $unit,
            'back' => 'subject_list.php'
        ]);
    }

    public function edit(): void
    {
        Auth::requireRole(['admin', 'staff']);

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            SessionManager::setError('Invalid subject ID.');
            Redirect::to('subject_list.php');
        }

        $subjectModel = new Subject();
        $result = $subjectModel->getById($id);

        if (!$result || $result->num_rows === 0) {
            SessionManager::setError('Subject not found.');
            Redirect::to('subject_list.php');
        }

        $subject = $result->fetch_assoc();
        $code = $subject['code'];
        $title = $subject['title'];
        $unit = $subject['unit'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $unit = trim($_POST['unit'] ?? '');

            if (!Validator::required($code)) {
                $errors[] = 'Code is required.';
            }
            if (!Validator::required($title)) {
                $errors[] = 'Title is required.';
            }
            if (!Validator::numeric($unit) || floatval($unit) <= 0 || !Subject::validateUnit($unit)) {
                $errors[] = 'Unit must be a number between 0.5 and 10.';
            }

            if (empty($errors)) {
                if ($subjectModel->update($id, $code, $title, floatval($unit))) {
                    SessionManager::setSuccess('Subject updated successfully!');
                    Redirect::to('subject_list.php');
                } else {
                    $errors[] = 'Database error: could not update subject.';
                }
            }
        }

        $this->render(__DIR__ . '/../Views/subject/form.php', [
            'action' => 'Edit',
            'errors' => $errors,
            'code' => $code,
            'title' => $title,
            'unit' => $unit,
            'id' => $id,
            'back' => 'subject_list.php'
        ]);
    }
}
