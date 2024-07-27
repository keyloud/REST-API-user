<?php
// Устанавливаем лимит памяти для предотвращения исчерпания памяти
ini_set('memory_limit', '256M');

header('Content-Type: application/json');

// Подключаем необходимые файлы
require 'config.php';
require 'user.php';

// Получаем метод запроса и путь
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Логирование для отладки
file_put_contents('debug.log', "Request method: $method\n", FILE_APPEND);
file_put_contents('debug.log', "Request path: $path\n", FILE_APPEND);
file_put_contents('debug.log', "Path parts: " . print_r($pathParts, true) . "\n", FILE_APPEND);

// Проверяем первый элемент пути для маршрутизации
if ($pathParts[0] === 'user') {
    switch ($method) {
        case 'POST':
            file_put_contents('debug.log', "Handling POST request\n", FILE_APPEND);
            createUser();
            break;
        default:
            file_put_contents('debug.log', "Method not allowed\n", FILE_APPEND);
            http_response_code(405);
            echo json_encode(['message' => 'Метод не разрешен']);
    }
} else {
    file_put_contents('debug.log', "Not found\n", FILE_APPEND);
    http_response_code(404);
    echo json_encode(['message' => 'Не найдено']);
}
?>
