<?php
namespace App;

class Router {
    public function run() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if ($uri === '/' && $method == 'GET') {
            echo json_encode(['message' => 'API To-Do-List está rodando 🚀']);
            return;
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found :(']);
    }
}