<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOStatement;

/** Dasar semua Model (Entity). Semua query memakai prepared statement. */
abstract class Model
{
    protected function pdo(): PDO { return Database::pdo(); }

    protected function run(string $sql, array $params = []): PDOStatement
    {
        $st = $this->pdo()->prepare($sql);
        $st->execute($params);
        return $st;
    }

    protected function all(string $sql, array $params = []): array { return $this->run($sql, $params)->fetchAll(); }

    protected function one(string $sql, array $params = []): ?array
    {
        $r = $this->run($sql, $params)->fetch();
        return $r === false ? null : $r;
    }

    protected function scalar(string $sql, array $params = [])
    {
        return $this->run($sql, $params)->fetchColumn();
    }

    protected function lastId(): int { return (int)$this->pdo()->lastInsertId(); }
}
