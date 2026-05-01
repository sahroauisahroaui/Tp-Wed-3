<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];

    // كود الإدخال في جدول grades الذي أنشأتِيه
    $sql = "INSERT INTO grades (student_id, course_id, grade) VALUES ('$student_id', '$course_id', '$grade')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>تم تسجيل العلامة بنجاح!</div>";
    } else {
        echo "خطأ: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="post" style="padding: 20px; direction: rtl;">
    <label>رقم الطالب:</label><br>
    <input type="number" name="student_id" required><br><br>
    
    <label>رقم المادة:</label><br>
    <input type="number" name="course_id" required><br><br>
    
    <label>العلامة (من 4.0):</label><br>
    <input type="number" step="0.01" name="grade" required><br><br>
    
    <button type="submit">حفظ في قاعدة البيانات</button>
</form>

