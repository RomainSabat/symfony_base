<?php

namespace App;

use PDO;

class DatabaseGateway
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Retourne les noms de tables d’une base SQLite
    public function listDbs(): array
    {
        $query = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table';");
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }
}
