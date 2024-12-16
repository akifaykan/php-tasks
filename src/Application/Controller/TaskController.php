<?php

namespace Application\Controller;

use Core\Entity\Priority;
use Core\Service\TaskService;

class TaskController
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function addTask(
        string $id,
        string $title,
        string $priority = 'medium',
        ?string $completionDate = null): void
    {
        $priorityEnum = Priority::tryFrom($priority) ?? Priority::MEDIUM;
        $this->taskService->addTask($id, $title, $priorityEnum, $completionDate);
        echo "Task '{$title}' added successfully. <br>";
    }

    public function listTasks(): void
    {
        $tasks = $this->taskService->getAllTasks();

        foreach ($tasks as $task) {
            echo "<br> ID: {$task->getId()} | Title: {$task->getTitle()} | Status: {$task->getStatus()} | Priority: {$task->getPriority()->value} | Completion Date: {$task->getCompletionDate()}";
        }
    }

    public function completeTask(string $id): void
    {
        try {
            $this->taskService->completeTask($id);
            echo "Task with ID '{$id}' marked as completed. <br>";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function deleteTask(string $id): void
    {
        $this->taskService->deleteTask($id);
        echo "Task with ID '{$id}' deleted successfully. <br>";
    }

    public function updateTask(string $id, string $title, string $priority = 'medium'): void
    {
        try {
            $priorityEnum = Priority::tryFrom($priority) ?? Priority::MEDIUM;
            $this->taskService->updateTask($id, $title, $priorityEnum);
            echo "Task '{$id}' updated successfully.<br><br>";
        } catch (\Exception $e) {
            echo $e->getMessage() . " <br><br>";
        }
    }
}
