<?php

ini_set('memory_limit', '256M'); 

require './config/config.php';
require '../vendor/autoload.php';

header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

try {
    switch ($requestMethod) {
        case 'GET':
            if (isset($path[0])) {
                getProduct($path[0]);
            } else {
                getProducts();
            }
            break;
        case 'POST':
            addProduct();
            break;
        case 'PUT':
            if (isset($path[0])) {
                updateProduct($path[0]);
            }
            break;
        case 'DELETE':
            if (isset($path[0])) {
                deleteProduct($path[0]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error', 'error' => $e->getMessage()]);
}

function getProducts() {
    try {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT * FROM wp_k_products LIMIT 256'); // Limite à 256 résultats
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to fetch products', 'error' => $e->getMessage()]);
    }
}

function getProduct($id) {
    try {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT product_aid, product_name_en, product_priceUSD, product_stock_units FROM wp_k_products WHERE product_aid = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to fetch product', 'error' => $e->getMessage()]);
    }
}

function validateProductData($data) {
    return isset($data['product_name_en']) && !empty($data['product_name_en']) &&
           isset($data['product_priceUSD']) && is_numeric($data['product_priceUSD']) && $data['product_priceUSD'] > 0 &&
           isset($data['product_stock_units']) && is_numeric($data['product_stock_units']) && $data['product_stock_units'] >= 0;
}

function addProduct() {
    try {
        $pdo = Database::connect();
        $data = json_decode(file_get_contents('php://input'), true);
        if (validateProductData($data)) {
            $stmt = $pdo->prepare('INSERT INTO wp_k_products (product_name_en, product_priceUSD, product_stock_units) VALUES (?, ?, ?)');
            $stmt->execute([$data['product_name_en'], $data['product_priceUSD'], $data['product_stock_units']]);
            $productId = $pdo->lastInsertId();
            $stmt = $pdo->prepare('SELECT product_aid, product_name_en, product_priceUSD, product_stock_units FROM wp_k_products WHERE product_aid = ?');
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['message' => 'Product added', 'product' => $product]);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to add product', 'error' => $e->getMessage()]);
    }
}

function updateProduct($id) {
    try {
        $pdo = Database::connect();
        $data = json_decode(file_get_contents('php://input'), true);
        if (validateProductData($data)) {
            $stmt = $pdo->prepare('UPDATE wp_k_products SET product_name_en = ?, product_priceUSD = ?, product_stock_units = ? WHERE product_aid = ?');
            $stmt->execute([$data['product_name_en'], $data['product_priceUSD'], $data['product_stock_units'], $id]);
            echo json_encode(['message' => 'Product updated']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to update product', 'error' => $e->getMessage()]);
    }
}

function deleteProduct($id) {
    try {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('DELETE FROM wp_k_products WHERE product_aid = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['message' => 'Product deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to delete product', 'error' => $e->getMessage()]);
    }
}