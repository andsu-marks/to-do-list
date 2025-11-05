<?php
namespace App\Controllers;

use App\Models\Task;

class TaskController {
    public function run() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $task = new Task();

        if ($uri === '/' && $method == 'GET') {
            echo json_encode(['message' => 'API To-Do-List is running 🚀']);
            return;
        }

        if ($uri === '/tasks') {
            if ($method == 'GET') {
                echo json_encode($task->getAll(), JSON_PRETTY_PRINT);
                return;
            } else if ($method == 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $task->save([
                    'uid' => uniqid(),
                    'title' => $input['title'],
                    'status' => $input['status'] ?? 'To Do',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                http_response_code(201);
                echo json_encode(['message' => 'Task created successfully!']);
                return;
            }
        }
        
        if (preg_match('#^/tasks/([a-zA-Z0-9]+)$#', $uri, $matches)) {
            $uid = $matches[1];
            
            if ($method == 'GET') {
                $taskData = $task->getByUid($uid);
                if ($taskData) {
                    echo json_encode($taskData, JSON_PRETTY_PRINT);
                }

                return;
            } elseif ($method == 'PUT') {
                $input = json_decode(file_get_contents('php://input'), true);
                $updated = $task->update($uid, $input);

                if ($updated) {
                    echo json_encode(['message' => 'Task Updated Successfully!']);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Task not found']);
                }

                return;
            } elseif($method == 'DELETE') {
                $deleted = $task->delete($uid);

                if ($deleted) {
                    echo json_encode(['message' => 'Task deleted successfully!']);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Task not found']);
                }
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found :(']);
    }
}