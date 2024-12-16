<?php

namespace Core\Interfaces;

use Core\Entity\Task;

interface TaskRepositoryInterface
{
    public function save(Task $task): void;
    public function getAll(): array;
    public function findById(string $id): ?Task;
    public function delete(string $id): void;
}
