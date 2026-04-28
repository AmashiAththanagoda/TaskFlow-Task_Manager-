<?php

require_once __DIR__ . '/../config/db.php';

define('TASKS_PER_PAGE', 5);

class Task {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(int $userId, string $search = '', string $filter = 'all', int $page = 1, int $perPage = TASKS_PER_PAGE): array {
        $offset = ($page - 1) * $perPage;

        $conditions = ['user_id = :user_id'];
        $params = [':user_id' => $userId];

        if (!empty($search)) {
            $conditions[] = 'title LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }

        if ($filter !== 'all' && in_array($filter, ['pending', 'completed'])) {
            $conditions[] = 'status = :status';
            $params[':status'] = $filter;
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);

        $sql = "SELECT * FROM tasks {$where} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll(int $userId, string $search = '', string $filter = 'all'): int {
        $conditions = ['user_id = :user_id'];
        $params = [':user_id' => $userId];

        if (!empty($search)) {
            $conditions[] = 'title LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }

        if ($filter !== 'all' && in_array($filter, ['pending', 'completed'])) {
            $conditions[] = 'status = :status';
            $params[':status'] = $filter;
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks {$where}");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function getById(int $id, int $userId): array|false {
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE id = :id AND user_id = :user_id LIMIT 1');
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            'INSERT INTO tasks (user_id, title, description, due_date, status)
             VALUES (:user_id, :title, :description, :due_date, :status)'
        );
        return $stmt->execute([
            ':user_id'     => $data['user_id'],
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
            ':due_date'    => $data['due_date']    ?: null,
            ':status'      => $data['status']      ?? 'pending',
        ]);
    }

    public function update(int $id, array $data, int $userId): bool {
        $stmt = $this->db->prepare(
            'UPDATE tasks
             SET title = :title, description = :description, due_date = :due_date, status = :status
             WHERE id = :id AND user_id = :user_id'
        );
        return $stmt->execute([
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
            ':due_date'    => $data['due_date']    ?: null,
            ':status'      => $data['status']      ?? 'pending',
            ':id'          => $id,
            ':user_id'     => $userId,
        ]);
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    public function toggleStatus(int $id, int $userId): string|false {
        $task = $this->getById($id, $userId);
        if (!$task) return false;

        $newStatus = ($task['status'] === 'pending') ? 'completed' : 'pending';

        $stmt = $this->db->prepare(
            'UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([':status' => $newStatus, ':id' => $id, ':user_id' => $userId]);

        return $newStatus;
    }
}
