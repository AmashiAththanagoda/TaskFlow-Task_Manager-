<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/db.php';

class AuthController {

    private User $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
    }

    public function home(): void {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /tasks');
            exit;
        }
        require __DIR__ . '/../views/home/landing.php';
    }

    public function login(): void {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /tasks');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                $this->setFlash('error', 'Invalid form submission. Please try again.');
                header('Location: /login');
                exit;
            }

            $username = trim(htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'));
            $password = $_POST['password'] ?? '';

            $errors = [];

            if (empty($username)) $errors[] = 'Username is required.';
            if (empty($password)) $errors[] = 'Password is required.';

            if (empty($errors)) {
                $user = $this->userModel->findByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);

                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['username']  = $user['username'];

                    unset($_SESSION['csrf_token']);

                    $this->setFlash('success', 'Welcome back, ' . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . '!');

                    $redirect = $_SESSION['redirect_after_login'] ?? '/tasks';
                    unset($_SESSION['redirect_after_login']);
                    header('Location: ' . $redirect);
                    exit;
                } else {
                    $errors[] = 'Invalid username or password.';
                }
            }

            $this->renderView('auth/login', ['errors' => $errors, 'username' => $username]);
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->renderView('auth/login', ['errors' => [], 'username' => '']);
        }
    }

    public function register(): void {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /tasks');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                $this->setFlash('error', 'Invalid form submission. Please try again.');
                header('Location: /register');
                exit;
            }

            $username  = trim(htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'));
            $email     = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
            $password  = $_POST['password'] ?? '';
            $password2 = $_POST['password_confirm'] ?? '';

            $errors = [];

            if (empty($username) || strlen($username) < 3)   $errors[] = 'Username must be at least 3 characters.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))   $errors[] = 'Enter a valid email address.';
            if (strlen($password) < 8)                        $errors[] = 'Password must be at least 8 characters.';
            if ($password !== $password2)                     $errors[] = 'Passwords do not match.';

            if (empty($errors)) {
                if ($this->userModel->findByUsername($username)) {
                    $errors[] = 'That username is already taken.';
                } elseif ($this->userModel->findByEmail($email)) {
                    $errors[] = 'An account with that email already exists.';
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $this->userModel->create($username, $email, $hash);

                    $this->setFlash('success', 'Account created! Please log in.');
                    header('Location: /login');
                    exit;
                }
            }

            $this->renderView('auth/register', [
                'errors'   => $errors,
                'username' => $username,
                'email'    => $email,
            ]);
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->renderView('auth/register', ['errors' => [], 'username' => '', 'email' => '']);
        }
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }

    private function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function renderView(string $view, array $data = []): void {
        extract($data);

        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layouts/main.php';
    }
}
