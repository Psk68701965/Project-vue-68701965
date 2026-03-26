<?php

include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['customer_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "ข้อมูลไม่ถูกต้อง"
    ]);
    exit;
}

$customer_id = $data['customer_id'];
$updates = [];

if (isset($data['firstName'])) $updates[] = "firstName = :firstName";
if (isset($data['lastName'])) $updates[] = "lastName = :lastName";
if (isset($data['phone'])) $updates[] = "phone = :phone";
if (isset($data['username'])) $updates[] = "username = :username";
if (isset($data['password'])) $updates[] = "password = :password";

if (empty($updates)) {
    echo json_encode([
        "status" => "error",
        "message" => "ไม่มีข้อมูลที่จะอัปเดต"
    ]);
    exit;
}

try {
    $sql = "UPDATE customers SET " . implode(', ', $updates) . " WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($sql);

    if (isset($data['firstName'])) $stmt->bindParam(':firstName', $data['firstName']);
    if (isset($data['lastName'])) $stmt->bindParam(':lastName', $data['lastName']);
    if (isset($data['phone'])) $stmt->bindParam(':phone', $data['phone']);
    if (isset($data['username'])) $stmt->bindParam(':username', $data['username']);
    if (isset($data['password'])) $stmt->bindParam(':password', $data['password']);
    $stmt->bindParam(':customer_id', $customer_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "อัปเดตข้อมูลลูกค้าเรียบร้อย"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "ไม่สามารถอัปเดตข้อมูลได้"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

?>