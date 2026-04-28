<?php
$csrfToken  = $_SESSION['csrf_token'] ?? '';
$username   = htmlspecialchars($_SESSION['username'] ?? 'Guest');
$isLoggedIn = !empty($_SESSION['user_id']);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TaskFlow- Elegant task management.">
    <title><?= htmlspecialchars($pageTitle ?? 'TaskFlow') ?>- TaskFlow</title>
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:       #141420;
            --surface:  #1E1E2E;
            --raised:   #28283C;
            --border:   rgba(255,255,255,0.09);
            --border-h: rgba(157,143,245,0.40);
            --text:     #EEEEF5;
            --muted:    rgba(238,238,245,0.50);
            --dim:      rgba(238,238,245,0.28);
            --accent:   #9D8FF5;
            --accent-h: #8B7DE8;
            --success:  #4ADE80;
            --warning:  #FBBF24;
            --danger:   #F87171;
        }
        html { font-family: 'DM Sans', -apple-system, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        body { min-height: 100vh; line-height: 1.6; -webkit-font-smoothing: antialiased; }
        h1, h2, h3 { font-family: 'Cormorant', Georgia, serif; font-weight: 400; letter-spacing: -.01em; }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; }
        .card-hover { transition: border-color .2s, background .2s, box-shadow .2s; }
        .card-hover:hover { border-color: var(--border-h); background: var(--raised); box-shadow: 0 0 0 1px rgba(157,143,245,.1), 0 8px 28px rgba(0,0,0,.25); }

        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all .2s; text-decoration: none; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-h); box-shadow: 0 4px 20px rgba(139,121,245,.35); transform: translateY(-1px); }
        .btn-ghost { background: transparent; color: var(--muted); border: 1px solid var(--border); }
        .btn-ghost:hover { color: var(--text); border-color: var(--border-h); background: var(--raised); }
        .btn-danger-ghost { background: transparent; color: var(--danger); border: 1px solid rgba(248,113,113,.2); font-size: 12px; }
        .btn-danger-ghost:hover { background: rgba(248,113,113,.08); }

        .field { width: 100%; padding: 11px 14px; background: var(--raised); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 14px; font-family: inherit; transition: border-color .2s, box-shadow .2s; }
        .field::placeholder { color: var(--dim); }
        .field:focus { outline: none; border-color: rgba(139,121,245,.5); box-shadow: 0 0 0 3px rgba(139,121,245,.1); }
        select.field { cursor: pointer; }
        select.field option { background: #1C1C21; }
        textarea.field { resize: none; }

        .label { display: block; font-size: 11px; font-weight: 500; letter-spacing: .08em; text-transform: uppercase; color: var(--dim); margin-bottom: 8px; }

        .site-nav { border-bottom: 1px solid var(--border); background: rgba(20,20,32,.90); backdrop-filter: blur(20px); position: sticky; top: 0; z-index: 50; }
        .nav-inner { max-width: 1100px; margin: 0 auto; padding: 0 24px; height: 56px; display: flex; align-items: center; justify-content: space-between; }
        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon { width: 28px; height: 28px; background: var(--accent); border-radius: 8px; display: grid; place-items: center; }
        .logo-icon svg { width: 14px; height: 14px; color: #fff; }
        .logo-text { font-weight: 600; font-size: 15px; color: var(--text); letter-spacing: -.02em; }
        .nav-links { display: flex; align-items: center; gap: 6px; }
        .nav-link { font-size: 13px; color: var(--muted); text-decoration: none; padding: 6px 12px; border-radius: 8px; transition: color .2s, background .2s; }
        .nav-link:hover { color: var(--accent); background: rgba(157,143,245,.1); }
        .nav-divider { width: 1px; height: 16px; background: var(--border); margin: 0 6px; }
        .nav-user { font-size: 13px; color: var(--muted); }
        .nav-user strong { color: var(--accent); font-weight: 500; }

        .flash { padding: 12px 16px; border-radius: 10px; font-size: 13px; display: flex; align-items: center; gap: 10px; }
        .flash-success { background: rgba(74,222,128,.08); border: 1px solid rgba(74,222,128,.2); color: #4ADE80; }
        .flash-error   { background: rgba(248,113,113,.08); border: 1px solid rgba(248,113,113,.2); color: #F87171; }

        .badge { display: inline-flex; align-items: center; padding: 2px 9px; border-radius: 20px; font-size: 11px; font-weight: 500; letter-spacing: .02em; }
        .badge-pending   { background: rgba(251,191,36,.1);  color: var(--warning); border: 1px solid rgba(251,191,36,.2); }
        .badge-completed { background: rgba(74,222,128,.1);  color: var(--success); border: 1px solid rgba(74,222,128,.2); }
        .badge-overdue   { background: rgba(248,113,113,.1); color: var(--danger);  border: 1px solid rgba(248,113,113,.2); }

        .dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; margin-top: 6px; }
        .dot-pending   { background: var(--warning); }
        .dot-completed { background: var(--success); }
        .dot-overdue   { background: var(--danger); }

        .page { max-width: 1100px; margin: 0 auto; padding: 40px 24px; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp .4s ease both; }
        .d1 { animation-delay: .05s; } .d2 { animation-delay: .1s; } .d3 { animation-delay: .15s; }
        .d4 { animation-delay: .2s; }  .d5 { animation-delay: .25s; }

        .divider { height: 1px; background: var(--border); }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 3px; }

        .task-action { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; border: 1px solid transparent; transition: all .18s; text-decoration: none; background: transparent; }
        .task-action-edit     { color: var(--muted); border-color: var(--border); }
        .task-action-edit:hover { color: var(--accent); border-color: var(--border-h); background: rgba(157,143,245,.08); }
        .task-action-toggle   { color: var(--accent); border-color: rgba(157,143,245,.3); }
        .task-action-toggle:hover { background: rgba(157,143,245,.15); box-shadow: 0 0 12px rgba(157,143,245,.2); }
        .task-action-toggle-done { color: var(--muted); border-color: var(--border); }
        .task-action-toggle-done:hover { background: var(--raised); color: var(--text); border-color: var(--border-h); }
        .task-action-delete   { color: var(--dim); border-color: transparent; }
        .task-action-delete:hover { color: var(--danger); border-color: rgba(248,113,113,.3); background: rgba(248,113,113,.08); }

        .grad { background: linear-gradient(135deg, var(--text) 30%, var(--muted)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        @media (max-width: 860px) {
            #auth-panel, #create-panel, #edit-panel { display: none !important; }
            [style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
            [style*="min-height:calc(100vh - 56px)"] { min-height: auto !important; }
        }

        @media (max-width: 640px) {
            .page { padding: 20px 16px; }
            .site-nav .nav-inner { padding: 0 16px; }
            .nav-user { display: none; }

            .btn { padding: 9px 16px; font-size: 13px; }

            .card { border-radius: 12px; }

            [style*="grid-template-columns:repeat(3,1fr)"] { grid-template-columns: 1fr !important; }
            [style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
            [style*="grid-template-columns:repeat(2,1fr)"] { grid-template-columns: 1fr !important; }

            [style*="font-size:42px"] { font-size: 28px !important; }
            [style*="font-size:32px"] { font-size: 24px !important; }
            [style*="font-size:28px"] { font-size: 22px !important; }

            [style*="padding:32px 36px"] { padding: 20px 20px !important; }
            [style*="padding:32px"] { padding: 20px !important; }
            [style*="padding:28px"] { padding: 20px !important; }
            [style*="padding:48px 40px"] { padding: 32px 20px !important; }

            [style*="margin-bottom:36px"] { margin-bottom: 20px !important; }
            [style*="margin-bottom:40px"] { margin-bottom: 20px !important; }

            .task-action-edit span, .task-action span { display: none; }
            .task-action { padding: 6px 8px; }

            [style*="min-height:180px"] { min-height: 140px !important; }
        }
    </style>
</head>
<body>

<?php if ($isLoggedIn): ?>
<nav class="site-nav">
    <div class="nav-inner">
        <a href="/tasks" class="logo">
            <div class="logo-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="logo-text">TaskFlow</span>
        </a>
        <div class="nav-links">
            <a href="/tasks"        class="nav-link">Tasks</a>
            <a href="/tasks/create" class="nav-link">+ New</a>
            <div class="nav-divider"></div>
            <span class="nav-user">Hi, <strong><?= $username ?></strong></span>
            <a href="/logout" class="nav-link" style="color:var(--dim)">Sign out</a>
        </div>
    </div>
</nav>
<?php endif; ?>

<?php if (!empty($_SESSION['flash'])): ?>
<?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div style="max-width:1100px;margin:20px auto 0;padding:0 24px;">
    <div class="flash flash-dismiss <?= $flash['type']==='success' ? 'flash-success' : 'flash-error' ?>">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
            <?= $flash['type']==='success'
                ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>' ?>
        </svg>
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
    </div>
</div>
<?php endif; ?>

<main class="page">
    <?= $content ?? '' ?>
</main>

<footer style="border-top:1px solid var(--border);margin-top:80px;padding:24px;text-align:center">
    <p style="font-size:12px;color:var(--dim)">TaskFlow - Built with Core PHP and MySQL</p>
</footer>

<script>
document.querySelectorAll('.flash-dismiss').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .4s, transform .4s';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-6px)';
        setTimeout(() => el.remove(), 400);
    }, 5000);
});
</script>
</body>
</html>
