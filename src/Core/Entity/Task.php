<?php

namespace Core\Entity;

class Task
{
    private string $id;
    private string $title;
    private string $status;
    private Priority $priority;
    private ?string $completionDate;

    public function __construct(
        string $id,
        string $title,
        string $status = 'pending',
        Priority $priority = Priority::MEDIUM,
        ?string $completionDate = null
    ){
        $this->id = $id;
        $this->title = $title;
        $this->status = $status;
        $this->priority = $priority;
        $this->completionDate = $completionDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function markAsCompleted(): void
    {
        $this->status = 'completed';
    }

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function setPriority(Priority $priority): void
    {
        $this->priority = $priority;
    }

    public function getCompletionDate(): ?string
    {
        return $this->completionDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'priority' => $this->priority->value,
            'completion_date' => $this->completionDate,
        ];
    }
}
