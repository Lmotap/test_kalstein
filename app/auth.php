<?php

ini_set('memory_limit', '256M'); // Augmente la limite de mémoire à 256 Mo

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php'; // Charger les dépendances installées via Composer

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = '3d5f6a7b8c9d0e1f2a3b4c5d6e7f8g9h'; // Changez cette clé secrète

function authenticate()
{
    global $key; // Utilisez la clé globale
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded; // Retourner l'objet décodé
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'message' => 'Unauthorized',
                'error' => $e->getMessage(),
            ]);
            exit();
        }
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit();
    }
}

function generateToken($user)
{
    global $key; // Utilisez la clé globale
    $payload = [
        'iss' => 'http://yourdomain.com',
        'aud' => 'http://yourdomain.com',
        'iat' => time(),
        'nbf' => time(),
        'exp' => time() + 60 * 60, // 1 heure
        'data' => $user,
    ];
    return JWT::encode($payload, $key, 'HS256');
}

function checkRole($requiredRole)
{
    $user = authenticate();
    $pdo = Database::connect();
    $stmt = $pdo->prepare(
        'SELECT roles.name FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = ?'
    );
    $stmt->execute([$user->data->id]); // Accéder à la propriété en tant qu'objet
    $role = $stmt->fetchColumn();
    if ($role !== $requiredRole) {
        http_response_code(403);
        echo json_encode(['message' => 'Forbidden']);
        exit();
    }
}
?>
