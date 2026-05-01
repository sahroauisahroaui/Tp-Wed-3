<?php
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];
    $semester_id = 1; // قيمة افتراضية للفصل الدراسي

    // التأكد من وجود الطالب أولاً لتجنب خطأ Foreign Key
    $checkUser = $conn->query("SELECT id FROM users WHERE id = $student_id");
    
    if ($checkUser->num_rows > 0) {
        $sql = "INSERT INTO grades (student_id, course_id, semester_id, grade) 
                VALUES ('$student_id', '$course_id', '$semester_id', '$grade')
                ON DUPLICATE KEY UPDATE grade = '$grade'";

        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-success'>تم الحفظ بنجاح!</div>";
        } else {
            $message = "<div class='alert alert-danger'>خطأ: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>خطأ: رقم الطالب غير موجود في جدول المستخدمين.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>إضافة علامة</title>
</head>
<body class="bg-light p-5">
    <div class="container card p-4 shadow-sm" style="max-width: 500px;">
        <h4>إدخال علامة جديدة</h4>
        <?php echo $message; ?>
        <form method="post">
            <div class="mb-3">
                <label>رقم الطالب (ID):</label>
                <input type="number" name="student_id" class="form-control" required placeholder="مثلاً: 1">
            </div>
            <div class="mb-3">
                <label>رقم المادة:</label>
                <input type="number" name="course_id" class="form-control" required placeholder="مثلاً: 101">
            </div>
            <div class="mb-3">
                <label>العلامة:</label>
                <input type="number" step="0.01" name="grade" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">حفظ في قاعدة البيانات</button>
            <a href="index.php" class="btn btn-link w-100 mt-2">العودة للرئيسية</a>
        </form>
    </div>
</body>
</html>
