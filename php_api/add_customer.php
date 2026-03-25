<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

include 'condb.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$firstName = trim($input['firstName'] ?? '');
$lastName = trim($input['lastName'] ?? '');
$phone = trim($input['phone'] ?? '');
$username = trim($input['username'] ?? '');
$password = trim($input['password'] ?? '');

if (!$firstName || !$lastName || !$phone || !$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Required fields are missing']);
    exit;
}

try {
    $stmt = $conn->prepare('SELECT customer_id FROM customers WHERE username = :username');
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Username already exists']);
        exit;
    }

    $stmt = $conn->prepare('INSERT INTO customers (firstName, lastName, phone, username, password) VALUES (:firstName, :lastName, :phone, :username, :password)');
    $result = $stmt->execute([
        'firstName' => $firstName,
        'lastName' => $lastName,
        'phone' => $phone,
        'username' => $username,
        'password' => $password
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Registered successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Insert failed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
