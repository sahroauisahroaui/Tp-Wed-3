<?php
// 1. الاتصال بقاعدة البيانات في بداية الملف
include 'config.php';

// 2. منطق الحفظ عند إرسال النموذج عبر AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_action'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];
    $semester_id = 1; // قيمة افتراضية

    $sql = "INSERT INTO grades (student_id, course_id, semester_id, grade) 
            VALUES ('$student_id', '$course_id', '$semester_id', '$grade')
            ON DUPLICATE KEY UPDATE grade = '$grade'";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
    exit; // إنهاء التنفيذ لطلبات AJAX فقط
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام إدارة المعدل الأكاديمي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; margin-bottom: 20px; }
        .nav-pills .nav-link.active { background-color: #3498db; }
        .nav-link { color: #2c3e50; font-weight: 500; cursor: pointer; }
        .header-title { color: #2c3e50; font-weight: bold; margin-top: 30px; }
    </style>
</head>
<body>

<div class="container py-4">
    <h2 class="text-center header-title mb-4">نظام إدارة العلامات والمعدلات</h2>

    <ul class="nav nav-pills nav-justified mb-4 card p-2 bg-white" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="tab-home" data-bs-toggle="pill" data-bs-target="#view-home">الرئيسية والإحصائيات</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-add" data-bs-toggle="pill" data-bs-target="#view-add">إدخال العلامات</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="view-home">
            <div class="row">
                <div class="col-md-8">
                    <div class="card p-4">
                        <h4>سجل العلامات الأخير</h4>
                        <table class="table table-hover mt-3">
                            <thead class="table-light">
                                <tr><th>الطالب</th><th>المادة</th><th>العلامة</th></tr>
                            </thead>
                            <tbody id="gradesTableBody">
                                <?php 
                                $res = $conn->query("SELECT u.name as s_name, c.name as c_name, g.grade FROM grades g 
                                                   JOIN users u ON g.student_id = u.id 
                                                   JOIN courses c ON g.course_id = c.id LIMIT 5");
                                if ($res->num_rows > 0) {
                                    while($row = $res->fetch_assoc()) {
                                        echo "<tr><td>{$row['s_name']}</td><td>{$row['c_name']}</td><td><span class='badge bg-primary'>{$row['grade']}</span></td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>لا توجد بيانات حالياً</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 text-center">
                        <h4>تطور المعدل</h4>
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="view-add">
            <div class="card p-4 mx-auto" style="max-width: 600px;">
                <h4>إضافة أو تحديث علامة</h4>
                <form id="gradeForm">
                    <input type="hidden" name="ajax_action" value="1">
                    <div class="mb-3">
                        <label class="form-label">رقم الطالب (ID)</label>
                        <input type="number" name="student_id" class="form-control" placeholder="مثال: 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم المادة (ID)</label>
                        <input type="number" name="course_id" class="form-control" placeholder="مثال: 10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العلامة المستحقة</label>
                        <input type="number" step="0.01" name="grade" class="form-control" placeholder="0.00" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">حفظ في قاعدة البيانات</button>
                </form>
                <div id="responseMessage" class="mt-3"></div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // 1. جعل النموذج يعمل بالضغط والملء (AJAX) دون تحديث الصفحة
    document.getElementById('gradeForm').onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if(data.trim() === "success") {
                document.getElementById('responseMessage').innerHTML = '<div class="alert alert-success">تم الحفظ بنجاح! حدث الصفحة لرؤية النتائج.</div>';
                this.reset();
            } else {
                document.getElementById('responseMessage').innerHTML = '<div class="alert alert-danger">حدث خطأ في الحفظ.</div>';
            }
        });
    };

    // 2. كود الرسم البياني (Chart.js) لتظهر النتائج بشكل مرئي
    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['S1', 'S2', 'S3', 'S4'],
            datasets: [{
                label: 'معدل الفصول',
                data: [10, 12, 11.5, 14], // بيانات تجريبية
                borderColor: '#3498db',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(52, 152, 219, 0.1)'
            }]
        }
    });
</script>
</body>
</html>
