<?php

namespace Core\Service;

use Core\Entity\Priority;
use Core\Entity\Task;
use Core\Interfaces\TaskRepositoryInterface;

class TaskService
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addTask(
        string $id,
        string $title,
        Priority $priority,
        ?string $completionDate ): void
    {
        $task = new Task($id, $title, 'pending', $priority, $completionDate);
        $this->repository->save($task);
    }

    public function getAllTasks(): array
    {
        return $this->repository->getAll();
    }

    public function completeTask(string $id): void
    {
        $task = $this->repository->findById($id);

        if (!$task) {
            throw new \Exception("Task with id {$id} not found");
        }

        $task->markAsCompleted();
        $this->repository->save($task);
    }

    public function deleteTask(string $id): void
    {
        $this->repository->delete($id);
    }

    public function updateTask(string $id, string $title, Priority $priority): void
    {
        $task = $this->repository->findById($id);

        if (!$task) {
            throw new \Exception("Task with ID {$id} not found.");
        }

        $task->setTitle($title);
        $task->setPriority($priority);
        $this->repository->save($task);
    }
}
