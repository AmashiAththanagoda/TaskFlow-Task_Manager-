<?php
$pending = 0; $completed = 0;
foreach ($tasks as $t) { $t['status']==='completed' ? $completed++ : $pending++; }
?>

<div class="fade-up d1" style="position:relative;border-radius:20px;overflow:hidden;margin-bottom:36px;min-height:180px">
    <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1400&q=80"
         alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 60%;opacity:.55">
    <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(20,20,32,.92) 0%,rgba(30,30,50,.65) 100%)"></div>
    <div style="position:relative;z-index:1;display:flex;align-items:flex-end;justify-content:space-between;gap:16px;padding:32px 36px;height:100%;min-height:180px">
        <div style="align-self:flex-end">
            <p style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--dim);margin-bottom:8px">Workspace</p>
            <h1 style="font-size:42px;font-weight:400;letter-spacing:-.02em;color:var(--text);line-height:1">My Tasks</h1>
        </div>
        <a href="/tasks/create" class="btn btn-primary" style="align-self:flex-end;flex-shrink:0">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New task
        </a>
    </div>
</div>

<div class="fade-up d2" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:32px">
    <div class="card" style="padding:20px 24px">
        <p style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--dim);margin-bottom:8px">Total</p>
        <p style="font-size:28px;font-weight:300;letter-spacing:-.03em"><?= $totalTasks ?></p>
    </div>
    <div class="card" style="padding:20px 24px;border-color:rgba(251,191,36,.15)">
        <p style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--dim);margin-bottom:8px">Pending</p>
        <p style="font-size:28px;font-weight:300;letter-spacing:-.03em;color:var(--warning)"><?= $pending ?></p>
    </div>
    <div class="card" style="padding:20px 24px;border-color:rgba(74,222,128,.15)">
        <p style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--dim);margin-bottom:8px">Completed</p>
        <p style="font-size:28px;font-weight:300;letter-spacing:-.03em;color:var(--success)"><?= $completed ?></p>
    </div>
</div>

<form method="GET" action="/tasks" id="filter-form" class="fade-up d3"
      style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
    <div style="position:relative;flex:1;min-width:200px">
        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--dim);pointer-events:none" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
        </svg>
        <input id="search-input" type="text" name="search" class="field"
               value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
               placeholder="Search tasks..." style="padding-left:36px">
    </div>
    <select id="filter-select" name="filter" class="field" style="width:auto;padding:11px 14px"
            onchange="document.getElementById('filter-form').submit()">
        <option value="all"       <?= $filter==='all'       ?'selected':''?>>All</option>
        <option value="pending"   <?= $filter==='pending'   ?'selected':''?>>Pending</option>
        <option value="completed" <?= $filter==='completed' ?'selected':''?>>Completed</option>
    </select>
    <button type="submit" class="btn btn-ghost">Search</button>
    <?php if ($search || $filter !== 'all'): ?>
    <a href="/tasks" class="btn btn-ghost" style="color:var(--dim)">Clear</a>
    <?php endif; ?>
</form>

<?php if (empty($tasks)): ?>
<div class="fade-up d4" style="text-align:center;padding:80px 24px">
    <img src="https://images.unsplash.com/photo-1584949091598-c31daaaa4aa9?auto=format&fit=crop&w=300&q=80"
         alt="" style="width:100px;height:100px;object-fit:cover;border-radius:16px;margin:0 auto 24px;opacity:.35;display:block;filter:grayscale(1)">
    <p style="font-size:16px;font-weight:400;color:var(--muted);letter-spacing:-.01em">
        <?= ($search || $filter !== 'all') ? 'No tasks match your search.' : 'No tasks yet.' ?>
    </p>
    <?php if (!$search && $filter === 'all'): ?>
    <a href="/tasks/create" class="btn btn-primary" style="margin-top:20px;display:inline-flex">Create your first task</a>
    <?php endif; ?>
</div>

<?php else: ?>
<div style="display:flex;flex-direction:column;gap:2px" id="task-list">
<?php foreach ($tasks as $i => $task): ?>
<?php
$isCompleted = $task['status'] === 'completed';
$today = new DateTime();
$isOverdue = false;
if (!$isCompleted && !empty($task['due_date'])) {
    $due = DateTime::createFromFormat('Y-m-d', $task['due_date']);
    $isOverdue = $due && $due < $today->setTime(0,0);
}
$dotClass   = $isCompleted ? 'dot-completed' : ($isOverdue ? 'dot-overdue' : 'dot-pending');
$badgeClass = $isCompleted ? 'badge-completed' : ($isOverdue ? 'badge-overdue' : 'badge-pending');
$badgeText  = $isCompleted ? 'Completed' : ($isOverdue ? 'Overdue' : 'Pending');
$delay = min($i * 0.04, 0.32);
?>
<div class="card card-hover"
     id="task-card-<?= $task['id'] ?>"
     style="padding:16px 20px;display:flex;align-items:center;gap:16px;border-radius:12px;animation:fadeUp .35s ease both;animation-delay:<?= $delay ?>s">

    <div class="dot <?= $dotClass ?>" id="dot-<?= $task['id'] ?>"></div>

    <div style="flex:1;min-width:0">
        <p id="task-title-<?= $task['id'] ?>"
           style="font-size:14px;font-weight:500;color:<?= $isCompleted ? 'var(--dim)' : 'var(--text)' ?>;<?= $isCompleted ? 'text-decoration:line-through' : '' ?>;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            <?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>
        </p>
        <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap">
            <span id="badge-<?= $task['id'] ?>" class="badge <?= $badgeClass ?>"><?= $badgeText ?></span>
            <?php if (!empty($task['due_date'])): ?>
            <span style="font-size:11px;color:<?= $isOverdue ? 'var(--danger)' : 'var(--dim)' ?>">
                <?= htmlspecialchars(date('M j, Y', strtotime($task['due_date'])), ENT_QUOTES, 'UTF-8') ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($task['description'])): ?>
            <span style="font-size:12px;color:var(--dim);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:300px">
                <?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:6px;flex-shrink:0">
        <a href="/tasks/edit?id=<?= $task['id'] ?>" class="task-action task-action-edit">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            Edit
        </a>
        <button type="button"
                id="toggle-btn-<?= $task['id'] ?>"
                data-task-id="<?= $task['id'] ?>"
                data-status="<?= $task['status'] ?>"
                onclick="toggleTaskStatus(this)"
                class="task-action <?= $isCompleted ? 'task-action-toggle-done' : 'task-action-toggle' ?>">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span id="toggle-label-<?= $task['id'] ?>"><?= $isCompleted ? 'Reopen' : 'Complete' ?></span>
        </button>
        <form method="GET" action="/tasks/delete" onsubmit="return confirm('Delete this task?')" style="display:inline">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
            <button type="submit" class="task-action task-action-delete">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </form>
    </div>
</div>
<?php endforeach; ?>
</div>

<?php if ($totalPages > 1):
    $qParams = [];
    if ($search) $qParams['search'] = $search;
    if ($filter !== 'all') $qParams['filter'] = $filter;
?>
<div style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:32px">
    <?php if ($page > 1): ?>
    <a href="/tasks?<?= http_build_query(array_merge($qParams,['page'=>$page-1])) ?>" class="btn btn-ghost" style="padding:8px 14px">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>Prev
    </a>
    <?php endif; ?>
    <?php for ($i=1;$i<=$totalPages;$i++): ?>
    <a href="/tasks?<?= http_build_query(array_merge($qParams,['page'=>$i])) ?>"
       style="width:36px;height:36px;display:grid;place-items:center;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;transition:all .2s;
              <?= $i===$page ? 'background:var(--accent);color:#fff;' : 'color:var(--muted);border:1px solid var(--border);' ?>">
        <?= $i ?>
    </a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
    <a href="/tasks?<?= http_build_query(array_merge($qParams,['page'=>$page+1])) ?>" class="btn btn-ghost" style="padding:8px 14px">
        Next<svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>

<script>
async function toggleTaskStatus(btn) {
    const taskId    = btn.dataset.taskId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    btn.disabled = true;
    try {
        const res  = await fetch('/tasks/status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': csrfToken },
            body: new URLSearchParams({ task_id: taskId })
        });
        const data = await res.json();
        if (data.success) {
            const done   = data.new_status === 'completed';
            const badge  = document.getElementById('badge-'  + taskId);
            const label  = document.getElementById('toggle-label-' + taskId);
            const title  = document.getElementById('task-title-'   + taskId);
            const dot    = document.getElementById('dot-' + taskId);

            if (badge) { badge.textContent = done ? 'Completed' : 'Pending'; badge.className = 'badge ' + (done ? 'badge-completed' : 'badge-pending'); }
            if (label) label.textContent = done ? 'Reopen' : 'Complete';
            if (title) { title.style.textDecoration = done ? 'line-through' : 'none'; title.style.color = done ? 'var(--dim)' : 'var(--text)'; }
            if (dot)   { dot.className = 'dot ' + (done ? 'dot-completed' : 'dot-pending'); }

            btn.className = 'task-action ' + (done ? 'task-action-toggle-done' : 'task-action-toggle');
            btn.dataset.status = data.new_status;
        }
    } catch(e) { alert('Could not update status. Please refresh.'); }
    finally { btn.disabled = false; }
}
</script>
