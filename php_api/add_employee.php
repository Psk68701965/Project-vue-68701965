<?php

include 'condb.php';

// รับค่า JSON จาก client
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !is_array($data)) {
    echo json_encode([
        "status" => "error",
        "message" => "รูปแบบ JSON ไม่ถูกต้อง"
    ]);
    exit;
}

if (
    empty($data['emp_id']) ||
    empty($data['full_name']) ||
    empty($data['department']) ||
    !isset($data['salary']) ||
    !isset($data['active'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "กรุณากรอกข้อมูลให้ครบ"
    ]);
    exit;
}

try {
    // ตรวจสอบ emp_id ซ้ำ
    $check = $conn->prepare("SELECT 1 FROM employees WHERE emp_id = :emp_id LIMIT 1");
    $check->bindParam(':emp_id', $data['emp_id']);
    $check->execute();

    if ($check->fetch()) {
        echo json_encode([
            "status" => "error",
            "message" => "รหัสพนักงานนี้มีอยู่แล้ว"
        ]);
        exit;
    }

    $sql = "INSERT INTO employees (emp_id, full_name, department, salary, active)
            VALUES (:emp_id, :full_name, :department, :salary, :active)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':emp_id', $data['emp_id']);
    $stmt->bindParam(':full_name', $data['full_name']);
    $stmt->bindParam(':department', $data['department']);
    $stmt->bindParam(':salary', $data['salary']);
    $stmt->bindParam(':active', $data['active']);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "เพิ่มพนักงานสำเร็จ"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "ไม่สามารถเพิ่มข้อมูลได้"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

?>