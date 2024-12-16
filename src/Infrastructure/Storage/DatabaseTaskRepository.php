<?php

namespace Infrastructure\Storage;

use Core\Entity\Priority;
use Core\Entity\Task;
use Core\Interfaces\TaskRepositoryInterface;
use Dotenv\Dotenv;
use PDO;

class DatabaseTaskRepository implements TaskRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        $host = $_ENV["DB_HOST"];
        $port = $_ENV["DB_PORT"];
        $dbname = $_ENV["DB_DATABASE"];
        $username = $_ENV["DB_USERNAME"];
        $password = $_ENV["DB_PASSWORD"];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function save(Task $task): void
    {
        $sql = "INSERT INTO tasks (id, title, status, priority, completion_date) 
                VALUES (:id, :title, :status, :priority, :completion_date) 
                ON DUPLICATE KEY UPDATE 
                title = :title, status = :status, priority = :priority, completion_date = :completion_date";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'status' => $task->getStatus(),
            'priority' => $task->getPriority()->value,
            'completion_date' => $task->getCompletionDate(),
        ]);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM tasks";
        $stmt = $this->pdo->query($sql);

        $tasks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[$row['id']] = new Task(
                $row['id'],
                $row['title'],
                $row['status'],
                Priority::from($row['priority']),
                $row['completion_date']
            );
        }

        return $tasks;
    }

    public function findById(string $id): ?Task
    {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Task(
            $row['id'],
            $row['title'],
            $row['status'],
            Priority::from($row['priority']),
            $row['completion_date']
        );
    }

    public function delete(string $id): void
    {
        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
