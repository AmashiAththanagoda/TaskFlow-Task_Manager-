<?php

define('APP_ENV', 'development');

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

$projectRoot = dirname(__DIR__);
require_once $projectRoot . '/config/db.php';
require_once $projectRoot . '/middleware/auth.php';
require_once $projectRoot . '/models/User.php';
require_once $projectRoot . '/models/Task.php';
require_once $projectRoot . '/controllers/AuthController.php';
require_once $projectRoot . '/controllers/TaskController.php';

$url = trim($_GET['url'] ?? '', '/');

if (($qPos = strpos($url, '?')) !== false) {
    $url = substr($url, 0, $qPos);
}

$segments = array_filter(explode('/', $url));
$segments = array_values($segments);

$controller = $segments[0] ?? '';
$action     = $segments[1] ?? '';

$routes = [
    ''         => ['AuthController', 'home'],
    'home'     => ['AuthController', 'home'],
    'login'    => ['AuthController', 'login'],
    'register' => ['AuthController', 'register'],
    'logout'   => ['AuthController', 'logout'],

    'tasks'           => ['TaskController', 'index'],
    'tasks/create'    => ['TaskController', 'create'],
    'tasks/store'     => ['TaskController', 'store'],
    'tasks/edit'      => ['TaskController', 'edit'],
    'tasks/update'    => ['TaskController', 'update'],
    'tasks/delete'    => ['TaskController', 'delete'],
    'tasks/status'    => ['TaskController', 'toggleStatus'],
];

$routeKey = $url === '' ? '' : (count($segments) >= 2
    ? $segments[0] . '/' . $segments[1]
    : $segments[0]);

if (array_key_exists($routeKey, $routes)) {
    [$controllerClass, $method] = $routes[$routeKey];

    $controllerInstance = new $controllerClass();

    if (method_exists($controllerInstance, $method)) {
        $controllerInstance->$method();
    } else {
        http_response_code(500);
        echo 'Internal error: method not found.';
    }
} else {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script></head>
    <body class="min-h-screen bg-slate-50 flex items-center justify-center font-sans">
    <div class="text-center space-y-4">
        <p class="text-8xl font-bold text-indigo-200">404</p>
        <h1 class="text-2xl font-semibold text-slate-700">Page Not Found</h1>
        <p class="text-slate-500">The page you\'re looking for doesn\'t exist.</p>
        <a href="/tasks" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            Go to My Tasks
        </a>
    </div></body></html>';
}
