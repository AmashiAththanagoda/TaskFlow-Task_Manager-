<div style="display:flex;min-height:calc(100vh - 56px);margin:-40px -24px">

    <div style="flex:0 0 480px;position:relative;overflow:hidden;display:none" id="auth-panel">
        <img src="https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&fit=crop&w=960&q=85"
             alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.45">
        <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(20,20,32,0) 0%,#141420 100%)"></div>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:48px 40px">
            <div style="margin-bottom:8px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--dim)">TaskFlow</div>
            <h2 style="font-size:32px;font-weight:400;line-height:1.25;color:var(--text)">Start your<br>journey today.</h2>
            <p style="margin-top:12px;font-size:14px;color:var(--muted);line-height:1.7">Simple. Secure. Beautiful.<br>Everything a task manager should be.</p>
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
                <h1 style="font-size:26px;letter-spacing:-.02em;color:var(--text)">Create account</h1>
                <p style="font-size:14px;color:var(--muted);margin-top:6px">Free forever. No card required.</p>
            </div>

            <form method="POST" action="/register" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <div style="margin-bottom:16px">
                    <label class="label" for="reg-username">Username</label>
                    <input class="field" id="reg-username" type="text" name="username"
                           value="<?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           autocomplete="username" required minlength="3" placeholder="At least 3 characters">
                </div>

                <div style="margin-bottom:16px">
                    <label class="label" for="reg-email">Email</label>
                    <input class="field" id="reg-email" type="email" name="email"
                           value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           autocomplete="email" required placeholder="you@example.com">
                </div>

                <div style="margin-bottom:16px">
                    <label class="label" for="reg-password">Password</label>
                    <input class="field" id="reg-password" type="password" name="password"
                           autocomplete="new-password" required minlength="8" placeholder="Minimum 8 characters">
                </div>

                <div style="margin-bottom:28px">
                    <label class="label" for="reg-confirm">Confirm Password</label>
                    <input class="field" id="reg-confirm" type="password" name="password_confirm"
                           autocomplete="new-password" required placeholder="Repeat password">
                </div>

                <button id="reg-submit" type="submit" class="btn btn-primary"
                        style="width:100%;justify-content:center;padding:12px">
                    Create account
                </button>
            </form>

            <p style="margin-top:20px;font-size:13px;color:var(--dim);text-align:center">
                Already have an account?
                <a href="/login" style="color:var(--accent);text-decoration:none;font-weight:500"> Sign in</a>
            </p>

        </div>
    </div>
</div>

<style>
@media (min-width: 900px) { #auth-panel { display: block !important; } }
</style>
