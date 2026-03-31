<?php

namespace App\Controllers;

use App\Core\SessionManager;

class BaseController
{
    protected function beforeAction(): void
    {
        // Future hook for middlewares
    }

    protected function render(string $viewPath, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        include $viewPath;
    }

    protected function setSuccess(string $message): void
    {
        SessionManager::setSuccess($message);
    }

    protected function setError(string $message): void
    {
        SessionManager::setError($message);
    }
}
