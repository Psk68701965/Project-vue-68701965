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

$emp_id = $data['emp_id'];
$updates = [];

if (isset($data['full_name'])) $updates[] = "full_name = :full_name";
if (isset($data['department'])) $updates[] = "department = :department";
if (isset($data['salary'])) $updates[] = "salary = :salary";
if (isset($data['active'])) $updates[] = "active = :active";

if (empty($updates)) {
    echo json_encode([
        "status" => "error",
        "message" => "ไม่มีข้อมูลที่จะอัปเดต"
    ]);
    exit;
}

try {
    $sql = "UPDATE employees SET " . implode(', ', $updates) . " WHERE emp_id = :emp_id";
    $stmt = $conn->prepare($sql);

    if (isset($data['full_name'])) $stmt->bindParam(':full_name', $data['full_name']);
    if (isset($data['department'])) $stmt->bindParam(':department', $data['department']);
    if (isset($data['salary'])) $stmt->bindParam(':salary', $data['salary']);
    if (isset($data['active'])) $stmt->bindParam(':active', $data['active']);
    $stmt->bindParam(':emp_id', $emp_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "อัปเดตข้อมูลพนักงานเรียบร้อย"
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