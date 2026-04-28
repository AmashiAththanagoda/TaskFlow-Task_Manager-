<div style="display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 56px);margin:-40px -24px;gap:0">

    <div style="position:relative;overflow:hidden;display:none" id="edit-panel">
        <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=85"
             alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.4">
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(20,20,32,.92) 0%,rgba(30,30,50,.65) 100%)"></div>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:56px 48px">
            <p style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(238,238,245,.4);margin-bottom:12px">Editing</p>
            <h2 style="font-size:36px;font-weight:400;line-height:1.2;color:#EEEEF5">Refine your<br>focus.</h2>
            <p style="margin-top:16px;font-size:14px;color:rgba(238,238,245,.55);line-height:1.8;max-width:320px">Update the details, adjust your timeline, or mark it as done. Every revision moves you forward.</p>
            <div style="margin-top:36px;background:rgba(157,143,245,.08);border:1px solid rgba(157,143,245,.15);border-radius:12px;padding:16px 20px">
                <p style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:rgba(238,238,245,.35);margin-bottom:8px">Currently editing</p>
                <p style="font-size:15px;font-weight:500;color:#EEEEF5;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    <?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p style="font-size:12px;margin-top:4px;color:rgba(238,238,245,.4)">
                    Status: <span style="color:<?= $task['status']==='completed' ? 'var(--success)' : 'var(--warning)' ?>"><?= ucfirst($task['status']) ?></span>
                </p>
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
                <h1 style="font-size:28px;color:var(--text)">Edit task</h1>
                <p style="font-size:14px;color:var(--muted);margin-top:6px">Update the details and save your changes.</p>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="flash flash-error" style="margin-bottom:20px">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div><?php foreach ($errors as $e) echo '<p>' . htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . '</p>'; ?></div>
            </div>
            <?php endif; ?>

            <div class="card" style="padding:28px;margin-bottom:16px">
                <form method="POST" action="/tasks/update" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="task_id"    value="<?= (int) $task['id'] ?>">

                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="edit-title">
                            Title <span style="color:var(--danger)">*</span>
                        </label>
                        <input class="field" id="edit-title" type="text" name="title" required maxlength="255"
                               value="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>"
                               placeholder="What needs to be done?">
                        <p style="font-size:11px;color:var(--dim);text-align:right;margin-top:5px" id="edit-count">0 / 255</p>
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="edit-desc">
                            Description <span style="font-size:10px;font-weight:400;text-transform:none;letter-spacing:0;color:var(--dim)">(optional)</span>
                        </label>
                        <textarea class="field" id="edit-desc" name="description" rows="3"
                                  placeholder="Add notes or context..."><?= htmlspecialchars($task['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:28px">
                        <div>
                            <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="edit-due">
                                Due Date
                            </label>
                            <input class="field" id="edit-due" type="date" name="due_date"
                                   value="<?= htmlspecialchars($task['due_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div>
                            <label style="display:block;font-size:12px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px" for="edit-status">
                                Status
                            </label>
                            <select class="field" id="edit-status" name="status">
                                <option value="pending"   <?= $task['status']==='pending'   ? 'selected' : '' ?>>Pending</option>
                                <option value="completed" <?= $task['status']==='completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px">
                        <button id="edit-submit" type="submit" class="btn btn-primary" style="flex:1;justify-content:center">Save changes</button>
                        <a href="/tasks" class="btn btn-ghost">Cancel</a>
                    </div>
                </form>
            </div>

            <div class="card" style="padding:18px 24px;border-color:rgba(248,113,113,.12)">
                <p style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--dim);margin-bottom:12px">Danger zone</p>
                <a href="/tasks/delete?id=<?= (int) $task['id'] ?>"
                   onclick="return confirm('Delete this task permanently?')"
                   class="btn btn-danger-ghost">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete this task
                </a>
            </div>

        </div>
    </div>
</div>

<style>
@media (min-width: 860px) { #edit-panel { display: block !important; } }
</style>

<script>
const et = document.getElementById('edit-title'), ec = document.getElementById('edit-count');
if (et && ec) { const u = () => { ec.textContent = et.value.length + ' / 255'; }; et.addEventListener('input', u); u(); }
</script>
