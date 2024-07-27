<?php
function createUser() {
    global $pdo;

    // Чтение и декодирование входных данных из тела запроса
    $data = json_decode(file_get_contents('php://input'), true);

    // Логирование для отладки
    file_put_contents('debug.log', "Input data: " . print_r($data, true) . "\n", FILE_APPEND);

    // Проверка наличия необходимых полей в данных
    if (!isset($data['name'], $data['email'], $data['password'])) {
        file_put_contents('debug.log', "Invalid input\n", FILE_APPEND);
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        return;
    }

    try {
        // Подготовка и выполнение запроса на вставку нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'],$data['email'], password_hash($data['password'], PASSWORD_DEFAULT)]);

        file_put_contents('debug.log', "User created\n", FILE_APPEND);
        echo json_encode(['message' => 'User created']);
    } catch (Exception $e) {
        // Логирование ошибки и возврат ответа с ошибкой
        file_put_contents('debug.log', "Error in createUser: " . $e->getMessage() . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['message' => 'Internal server error', 'error' => $e->getMessage()]);
    }
}
?>
