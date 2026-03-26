<?php

include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['emp_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "ข้อมูลไม่ถูกต้อง"
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = :emp_id");
    $stmt->bindParam(':emp_id', $data['emp_id']);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "ลบข้อมูลพนักงานเรียบร้อย"
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