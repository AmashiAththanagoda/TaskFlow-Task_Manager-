<div style="display:flex;min-height:calc(100vh - 56px);margin:-40px -24px">

    <div style="flex:0 0 480px;position:relative;overflow:hidden;display:none" id="auth-panel">
        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=960&q=85"
             alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.5">
        <div style="position:absolute;inset:0;background:linear-gradient(to right, rgba(12,12,15,0) 0%, #141420 100%)"></div>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:48px 40px">
            <div style="margin-bottom:8px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--dim)">TaskFlow</div>
            <h2 style="font-size:32px;font-weight:400;line-height:1.25;color:var(--text)">Focus on what<br>matters most.</h2>
            <p style="margin-top:12px;font-size:14px;color:var(--muted);line-height:1.7">Your tasks, beautifully organised.<br>Nothing more. Nothing less.</p>
        </div>
    </div>

    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px">
        <div class="fade-up" style="width:100%;max-width:380px">

            <?php if (!empty($errors)): ?>
            <div class="flash flash-error" style="margin-bottom:24px">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div><?php foreach ($errors as $e) echo '<p>' . htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . '</p>'; ?></div>
            </div>
            <?php endif; ?>

            <div style="margin-bottom:32px">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px">
                    <div class="logo-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;color:#fff">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span style="font-weight:600;font-size:15px;letter-spacing:-.02em">TaskFlow</span>
                </div>
                <h1 style="font-size:26px;letter-spacing:-.02em;color:var(--text)">Welcome back</h1>
                <p style="font-size:14px;color:var(--muted);margin-top:6px">Sign in to your workspace</p>
            </div>

            <form method="POST" action="/login" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <div style="margin-bottom:16px">
                    <label class="label" for="login-username">Username</label>
                    <input class="field" id="login-username" type="text" name="username"
                           value="<?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           autocomplete="username" required placeholder="Your username">
                </div>

                <div style="margin-bottom:24px">
                    <label class="label" for="login-password">Password</label>
                    <input class="field" id="login-password" type="password" name="password"
                           autocomplete="current-password" required placeholder="••••••••">
                </div>

                <button id="login-submit" type="submit" class="btn btn-primary"
                        style="width:100%;justify-content:center;padding:12px">
                    Sign in
                </button>
            </form>

            <p style="margin-top:20px;font-size:13px;color:var(--dim);text-align:center">
                No account?
                <a href="/register" style="color:var(--accent);text-decoration:none;font-weight:500"> Create one</a>
            </p>

            <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border)">
                <p style="font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--dim);margin-bottom:10px">Demo account</p>
                <div style="background:var(--raised);border:1px solid var(--border);border-radius:10px;padding:12px 14px">
                    <p style="font-size:13px;color:var(--muted)">
                        <span style="font-family:ui-monospace,monospace;color:var(--text)">demo_user</span>
                        &nbsp;/&nbsp;
                        <span style="font-family:ui-monospace,monospace;color:var(--text)">password</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@media (min-width: 900px) { #auth-panel { display: block !important; } }
</style>
