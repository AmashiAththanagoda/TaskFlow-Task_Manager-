<?php

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/db.php';

class TaskController {

    private Task $taskModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->taskModel = new Task();
    }

    public function index(): void {
        requireLogin();

        $userId = (int) $_SESSION['user_id'];

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $search = trim(htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'));
        $filter = in_array($_GET['filter'] ?? '', ['all', 'pending', 'completed'])
                    ? $_GET['filter']
                    : 'all';
        $page   = max(1, (int) ($_GET['page'] ?? 1));

        $totalTasks = $this->taskModel->countAll($userId, $search, $filter);

        $totalPages = (int) ceil($totalTasks / TASKS_PER_PAGE);
        $page       = min($page, max(1, $totalPages));

        $tasks = $this->taskModel->getAll($userId, $search, $filter, $page, TASKS_PER_PAGE);

        [$success, $error] = $this->consumeFlash();

        $this->renderView('tasks/index', [
            'tasks'       => $tasks,
            'search'      => $search,
            'filter'      => $filter,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'totalTasks'  => $totalTasks,
            'success'     => $success,
            'error'       => $error,
            'pageTitle'   => 'My Tasks',
        ]);
    }

    public function create(): void {
        requireLogin();

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        [$success, $error] = $this->consumeFlash();

        $this->renderView('tasks/create', [
            'errors'    => [],
            'old'       => [],
            'success'   => $success,
            'error'     => $error,
            'pageTitle' => 'New Task',
        ]);
    }

    public function store(): void {
        requireLogin();
        $this->requirePost();

        $this->verifyCsrf();

        $userId = (int) $_SESSION['user_id'];
        $data   = $this->sanitizeTaskInput($_POST);
        $errors = $this->validateTaskInput($data);

        if (!empty($errors)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->renderView('tasks/create', [
                'errors'    => $errors,
                'old'       => $data,
                'pageTitle' => 'New Task',
            ]);
            return;
        }

        $data['user_id'] = $userId;
        $this->taskModel->create($data);

        $this->setFlash('success', 'Task created successfully!');
        header('Location: /tasks');
        exit;
    }

    public function edit(): void {
        requireLogin();

        $id     = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];

        $task = $this->taskModel->getById($id, $userId);

        if (!$task) {
            $this->setFlash('error', 'Task not found or access denied.');
            header('Location: /tasks');
            exit;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        [$success, $error] = $this->consumeFlash();

        $this->renderView('tasks/edit', [
            'task'      => $task,
            'errors'    => [],
            'success'   => $success,
            'error'     => $error,
            'pageTitle' => 'Edit Task',
        ]);
    }

    public function update(): void {
        requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $id     = (int) ($_POST['task_id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];

        $task = $this->taskModel->getById($id, $userId);
        if (!$task) {
            $this->setFlash('error', 'Task not found or access denied.');
            header('Location: /tasks');
            exit;
        }

        $data   = $this->sanitizeTaskInput($_POST);
        $errors = $this->validateTaskInput($data);

        if (!empty($errors)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->renderView('tasks/edit', [
                'task'      => array_merge($task, $data),
                'errors'    => $errors,
                'pageTitle' => 'Edit Task',
            ]);
            return;
        }

        $this->taskModel->update($id, $data, $userId);

        $this->setFlash('success', 'Task updated successfully!');
        header('Location: /tasks');
        exit;
    }

    public function delete(): void {
        requireLogin();

        $id     = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];

        if ($this->taskModel->delete($id, $userId)) {
            $this->setFlash('success', 'Task deleted.');
        } else {
            $this->setFlash('error', 'Could not delete task.');
        }

        header('Location: /tasks');
        exit;
    }

    public function toggleStatus(): void {
        requireLogin();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (empty($csrfHeader) || $csrfHeader !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
            exit;
        }

        $taskId = (int) ($_POST['task_id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];

        $newStatus = $this->taskModel->toggleStatus($taskId, $userId);

        if ($newStatus === false) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Task not found']);
            exit;
        }

        echo json_encode(['success' => true, 'new_status' => $newStatus]);
        exit;
    }


    private function sanitizeTaskInput(array $post): array {
        return [
            'title'       => trim(htmlspecialchars($post['title']       ?? '', ENT_QUOTES, 'UTF-8')),
            'description' => trim(htmlspecialchars($post['description'] ?? '', ENT_QUOTES, 'UTF-8')),
            'due_date'    => trim($post['due_date'] ?? ''),
            'status'      => in_array($post['status'] ?? '', ['pending', 'completed'])
                                ? $post['status']
                                : 'pending',
        ];
    }

    private function validateTaskInput(array $data): array {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        } elseif (strlen($data['title']) > 255) {
            $errors[] = 'Title must be 255 characters or fewer.';
        }

        if (!empty($data['due_date'])) {
            $d = DateTime::createFromFormat('Y-m-d', $data['due_date']);
            if (!$d || $d->format('Y-m-d') !== $data['due_date']) {
                $errors[] = 'Due date must be a valid date (YYYY-MM-DD).';
            }
        }

        return $errors;
    }

    private function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function consumeFlash(): array {
        $success = $error = null;
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            if ($flash['type'] === 'success') $success = $flash['message'];
            else                              $error   = $flash['message'];
        }
        return [$success, $error];
    }

    private function requirePost(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /tasks');
            exit;
        }
    }

    private function verifyCsrf(): void {
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $this->setFlash('error', 'Invalid form submission. Please try again.');
            header('Location: /tasks');
            exit;
        }
    }

    private function renderView(string $view, array $data = []): void {
        extract($data);

        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layouts/main.php';
    }
}
