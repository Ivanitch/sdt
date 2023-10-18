<?php

namespace App;
use PDO;
use PDOStatement;

class Query
{
    public function __construct(
        protected PDO $pdo
    )
    {
    }

    public function sql($query, $args = []): false|PDOStatement
    {
        return $this->run($query, $args);
    }

    protected function query($stmt): false|PDOStatement
    {
        return $this->pdo->query($stmt);
    }

    protected function prepare($stmt): false|PDOStatement
    {
        return $this->pdo->prepare($stmt);
    }

    protected function run($query, $args = []): false|PDOStatement
    {
        if (!$args) return $this->query($query);
        $stmt = $this->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }
}
