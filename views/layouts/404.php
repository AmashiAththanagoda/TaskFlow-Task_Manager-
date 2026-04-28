<?php
// Purpose: 404 Not Found fallback view

http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found | TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col items-center justify-center text-slate-800">
    <div class="text-center">
        <p class="text-8xl font-bold text-indigo-200">404</p>
        <h1 class="mt-4 text-2xl font-semibold text-slate-700">Page not found</h1>
        <p class="mt-2 text-slate-500">The page you're looking for doesn't exist or has been moved.</p>
        <a href="/tasks" class="mt-6 inline-block px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
            Go to My Tasks
        </a>
    </div>
</body>
</html>
