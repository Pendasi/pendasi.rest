<?php
namespace Pendasi\Rest\Core;

class Model {
    private $pdo;
    private string $table;
    private array $allowedColumns;

    public function __construct(Database $db, string $table, array $allowedColumns = []) {
        $this->pdo = $db->connection();
        $this->table = $table;
        $this->allowedColumns = $allowedColumns;
    }

    public function query(): QueryBuilder {
        return new QueryBuilder($this->pdo, $this->table, [], $this->allowedColumns);
    }

    private function filterColumns(array $data): array {
        if (!$this->allowedColumns) return $data;
        return array_filter($data, fn($k) => in_array($k, $this->allowedColumns), ARRAY_FILTER_USE_KEY);
    }

    public function all(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function find($id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): string {
        $data = $this->filterColumns($data);
        $cols = implode(",", array_keys($data));
        $vals = ":" . implode(",:", array_keys($data));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($cols) VALUES($vals)");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function update($id, array $data): bool {
        $data = $this->filterColumns($data);
        $cols = [];
        foreach ($data as $k => $v) $cols[] = "$k=:$k";
        $sql = "UPDATE {$this->table} SET ".implode(",", $cols)." WHERE id=:id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}