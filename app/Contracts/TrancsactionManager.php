<?php

namespace App\Contracts;

interface TrancsactionManager {
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}