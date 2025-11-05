<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\TaskController;

$taskController = new TaskController();
$taskController->run();