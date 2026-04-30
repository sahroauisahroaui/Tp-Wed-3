<?php
include 'db_connection.php'; // تأكدي من مسار ملف الاتصال
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=grades_report.csv');

$output = fopen('php://output', 'w');
// كتابة عناوين الأعمدة
fputcsv($output, array('رقم الطالب', 'اسم الطالب', 'المادة', 'الدرجة'));

// جلب البيانات من قاعدة البيانات
$query = "SELECT u.id, u.name, c.name as course, g.grade 
          FROM grades g 
          JOIN users u ON g.student_id = u.id 
          JOIN courses c ON g.course_id = c.id";

$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
?>

