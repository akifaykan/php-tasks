<?php

use Application\Controller\TaskController;
use Core\Helpers\FormatHelpers;
use Core\Service\TaskService;
use Infrastructure\Storage\DatabaseTaskRepository;
use Infrastructure\Storage\FileTaskRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$repository = new FileTaskRepository();
//$repository = new DatabaseTaskRepository();
$service = new TaskService($repository);
$controller = new TaskController($service);

$dateFormat = false ? FormatHelpers::formatDateTR() : date('d M Y');

echo "=== Task Management System ===<br><br>";

// Add Task
$controller->addTask('1', 'Learn PHP OOP', 'high', $dateFormat);
$controller->addTask('2', 'Learn PHP SOLID', 'low', $dateFormat);
$controller->addTask('3', 'Learn PHP DDD');
$controller->addTask('4', 'Make Coffie', 'high');
$controller->addTask('5', 'Last Task', 'low');

// Complate Task
/* $controller->completeTask('2'); */

// Update Task
/* $controller->updateTask('4', 'Make Coffie'); */

// Delete Task
/* $controller->deleteTask('7'); */

$controller->listTasks();

