<?php

namespace Infrastructure\Storage;

use Core\Entity\Priority;
use Core\Entity\Task;
use Core\Interfaces\TaskRepositoryInterface;

class FileTaskRepository implements TaskRepositoryInterface
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . "/tasks.json")
    {
        $this->filePath = $filePath;
    }

    public function save(Task $task): void
    {
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }

        $tasks = $this->getAll();

        $cleanTasks = [];
        foreach ($tasks as $existingTask) {
            $cleanTasks[$existingTask->getId()] = $existingTask->toArray();
        }

        $cleanTasks[$task->getId()] = $task->toArray();

        file_put_contents($this->filePath, json_encode($cleanTasks, JSON_PRETTY_PRINT));
    }

    public function getAll(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $data = file_get_contents($this->filePath);
        $tasksArray = json_decode($data, true);

        if (!is_array($tasksArray)) {
            return [];
        }

        $tasks = [];
        foreach ($tasksArray as $task) {
            if (is_array($task) && isset($task['id'], $task['title'], $task['status'])) {
                $tasks[$task['id']] = new Task(
                    $task['id'],
                    $task['title'],
                    $task['status'],
                    Priority::from($task['priority']),
                    $task['completion_date']
                );
            } else {
                error_log("Invalid task found: " . json_encode($task));
            }
        }

        return $tasks;
    }

    public function findById(string $id): ?Task
    {
        $tasks = $this->getAll();
        return $tasks[$id] ?? null;
    }

    public function delete(string $id): void
    {
        $tasks = $this->getAll();
        unset($tasks[$id]);
        file_put_contents($this->filePath, json_encode($tasks, JSON_PRETTY_PRINT));
    }
}
