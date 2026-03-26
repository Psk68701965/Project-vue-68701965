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

try {
    $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = :customer_id");
    $stmt->bindParam(':customer_id', $data['customer_id']);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "ลบข้อมูลลูกค้าเรียบร้อย"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "ไม่สามารถลบข้อมูลได้"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

?>