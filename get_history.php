<?php
include 'db_connection.php';
$studentId = $_GET['id'];

// استعلام لجلب تطور المعدل من جدول gpa_records (أو grades)
$query = "SELECT s.label as semester_label, g.grade as gpa 
          FROM grades g 
          JOIN semesters s ON g.semester_id = s.id 
          WHERE g.student_id = ? ORDER BY s.id ASC";

// ملاحظة: يجب استخدام Prepared Statements للحماية (Step 8)
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}

echo json_encode($history);
?>

