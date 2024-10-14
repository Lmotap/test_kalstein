<?php

require __DIR__ . '/config/config.php';
require __DIR__ . '/vendor/autoload.php'; 
require __DIR__ . '/app/auth.php'; 

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'];
$password = $data['password'];

try {
    $pdo = Database::connect();
    $stmt = $pdo->prepare('SELECT id, username, hashed_password, role FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['hashed_password'])) {
        $token = generateToken([
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ]);
        echo json_encode(['token' => $token]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid credentials']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error', 'error' => $e->getMessage()]);
}
?>