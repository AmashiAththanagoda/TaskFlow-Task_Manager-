<div style="display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 56px);margin:-40px -24px;gap:0">

    <div style="position:relative;overflow:hidden;display:none" id="create-panel">
        <img src="https://images.unsplash.com/photo-1471107340929-a87cd0f5b5f3?auto=format&fit=crop&w=900&q=85"
             alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.45">
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(20,20,32,.9) 0%,rgba(30,30,50,.6) 100%)"></div>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:56px 48px">
            <p style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(238,238,245,.4);margin-bottom:12px">New task</p>
            <h2 style="font-size:36px;font-weight:400;line-height:1.2;color:#EEEEF5">What will you<br>accomplish today?</h2>
            <p style="margin-top:16px;font-size:14px;color:rgba(238,238,245,.55);line-height:1.8;max-width:320px">Every great achievement starts with a single task. Write it down and make it real.</p>
            <div style="margin-top:40px;display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:6px;height:6px;border-radius:50%;background:#9D8FF5;flex-shrink:0"></div>
                    <span style="font-size:13px;color:rgba(238,238,245,.5)">Set a clear, actionable title</span>
                </div>
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:6px;height:6px;border-radius:50%;background:#9D8FF5;flex-shrink:0"></div>
                    <span style="font-size:13px;color:rgba(238,238,245,.5)">Add a due date to stay on track</span>
                </div>
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:6px;height:6px;border-radius:50%;background:#9D8FF5;flex-shrink:0"></div>
                    <span style="font-size:13px;color:rgba(238,238,245,.5)">Notes keep the context handy</span>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;align-items:center;justify-content:center;padding:48px 40px;overflow-y:auto">
        <div class="fade-up" style="width:100%;max-width:420px">

            <a href="/tasks" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:32px;transition:color .2s"
               onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--muted)'">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to tasks
            </a>

            <div style="margin-bottom:28px">
                <h1 style="font-size:28px;color:var(--text)">Create a task</h1>
                <p style="font-size:14px;color:var(--muted);margin-top:6px">Fill in the details and add it to your list.</p>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="flash flash-error" style="margin-bottom:20px">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div><?php foreach ($errors as $e) echo '<p>' . htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . '</p>'; ?></div>
            </div>
            <?php endif; ?>

            <div class="card" style="padding:28px">
                <form method="POST" action="/tasks/store" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="task-title">
                            Title <span style="color:var(--danger)">*</span>
                        </label>
                        <input class="field" id="task-title" type="text" name="title" required maxlength="255"
                               value="<?= htmlspecialchars($old['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               placeholder="What needs to be done?">
                        <p style="font-size:11px;color:var(--dim);text-align:right;margin-top:5px" id="title-count">0 / 255</p>
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="task-desc">
                            Description <span style="font-size:10px;font-weight:400;text-transform:none;letter-spacing:0;color:var(--dim)">(optional)</span>
                        </label>
                        <textarea class="field" id="task-desc" name="description" rows="3"
                                  placeholder="Add notes or context..."><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:28px">
                        <div>
                            <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="task-due">
                                Due Date <span style="font-size:10px;font-weight:400;text-transform:none;letter-spacing:0;color:var(--dim)">(optional)</span>
                            </label>
                            <input class="field" id="task-due" type="date" name="due_date"
                                   value="<?= htmlspecialchars($old['due_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div>
                            <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="task-status">
                                Status
                            </label>
                            <select class="field" id="task-status" name="status">
                                <option value="pending"   <?= ($old['status'] ?? 'pending') === 'pending'   ? 'selected' : '' ?>>Pending</option>
                                <option value="completed" <?= ($old['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px">
                        <button id="create-submit" type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                            Create task
                        </button>
                        <a href="/tasks" class="btn btn-ghost">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
@media (min-width: 860px) { #create-panel { display: block !important; } }
</style>

<script>
const ti = document.getElementById('task-title'), tc = document.getElementById('title-count');
if (ti && tc) { const u = () => { tc.textContent = ti.value.length + ' / 255'; }; ti.addEventListener('input', u); u(); }
</script>
