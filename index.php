<?php
[span_5](start_span)// استدعاء ملف الاتصال في بداية الصفحة[span_5](end_span)
include 'config.php';

[span_6](start_span)// معالجة طلبات الحفظ عبر AJAX[span_6](end_span)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_action'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];
    $semester_id = 1; [span_7](start_span)// قيمة افتراضية للفصل الدراسي[span_7](end_span)

    [span_8](start_span)// جملة الإدخال مع خاصية التحديث في حال تكرار القيد[span_8](end_span)
    $sql = "INSERT INTO grades (student_id, course_id, semester_id, grade) 
            VALUES ('$student_id', '$course_id', '$semester_id', '$grade')
            ON DUPLICATE KEY UPDATE grade = '$grade'";

    if ($conn->query($sql) === TRUE) {
        [span_9](start_span)echo "success";[span_9](end_span)
    } else {
        echo "error: " . [span_10](start_span)$conn->error;[span_10](end_span)
    }
    exit; [span_11](start_span)// إيقاف التنفيذ بعد الرد على الطلب[span_11](end_span)
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
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, sans-serif; [span_12](start_span)}
        .card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; border: none; }[span_12](end_span)
        .nav-pills .nav-link.active { background-color: #3498db; [span_13](start_span)}
    </style>
</head>
<body>

<div class="container py-4">
    <h2 class="text-center mb-4">نظام إدارة العلامات والمعدلات</h2>[span_13](end_span)

    <ul class="nav nav-pills nav-justified mb-4 card p-2 bg-white" id="pills-tab">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#view-home">الرئيسية</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#view-add">إدخال العلامات</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="view-home">
            <div class="row">
                <div class="col-md-8">
                    <div class="card p-4">
                        [span_14](start_span)<h4>سجل العلامات الأخير</h4>[span_14](end_span)
                        <table class="table table-hover mt-3">
                            <thead class="table-light">
                                <tr><th>الطالب</th><th>المادة</th><th>العلامة</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                [span_15](start_span)// جلب آخر 5 سجلات مع ربط الجداول[span_15](end_span)
                                $res = $conn->query("SELECT u.name as s_name, c.name as c_name, g.grade FROM grades g 
                                                   JOIN users u ON g.student_id = u.id 
                                                   JOIN courses c ON g.course_id = c.id LIMIT 5");
                                [span_16](start_span)if ($res && $res->num_rows > 0) {[span_16](end_span)
                                    while($row = $res->fetch_assoc()) {
                                        [span_17](start_span)echo "<tr><td>{$row['s_name']}</td><td>{$row['c_name']}</td><td><span class='badge bg-primary'>{$row['grade']}</span></td></tr>";[span_17](end_span)
                                    }
                                } else {
                                    [span_18](start_span)echo "<tr><td colspan='3' class='text-center'>لا توجد بيانات حالياً</td></tr>";[span_18](end_span)
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        [span_19](start_span)<canvas id="mainChart"></canvas>[span_19](end_span)
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="view-add">
            <div class="card p-4 mx-auto" style="max-width: 500px;">
                [span_20](start_span)<h4>إضافة علامة جديدة</h4>[span_20](end_span)
                <form id="gradeForm">
                    <input type="hidden" name="ajax_action" value="1">
                    <div class="mb-3">
                        <label>رقم الطالب (ID)</label>
                        [span_21](start_span)<input type="number" name="student_id" class="form-control" required>[span_21](end_span)
                    </div>
                    <div class="mb-3">
                        <label>رقم المادة (ID)</label>
                        [span_22](start_span)<input type="number" name="course_id" class="form-control" required>[span_22](end_span)
                    </div>
                    <div class="mb-3">
                        <label>العلامة</label>
                        [span_23](start_span)<input type="number" step="0.01" name="grade" class="form-control" required>[span_23](end_span)
                    </div>
                    <button type="submit" class="btn btn-primary w-100">حفظ البيانات</button>
                </form>
                [span_24](start_span)<div id="responseMessage" class="mt-3"></div>[span_24](end_span)
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    [span_25](start_span)// إرسال النموذج بدون تحديث الصفحة[span_25](end_span)
    document.getElementById('gradeForm').onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('index.php', { method: 'POST', body: formData })
        .then(r => r.text())
        .then(data => {
            if(data.trim() === "success") {
                [span_26](start_span)document.getElementById('responseMessage').innerHTML = '<div class="alert alert-success">تم الحفظ!</div>';[span_26](end_span)
                this.reset();
            } else {
                document.getElementById('responseMessage').innerHTML = '<div class="alert alert-danger">خطأ في الحفظ</div>';
            }
        });
    };

    [span_27](start_span)// رسم بياني بسيط[span_27](end_span)
    new Chart(document.getElementById('mainChart'), {
        type: 'line',
        data: {
            labels: ['S1', 'S2', 'S3', 'S4'],
            datasets: [{ label: 'المعدل', data: [10, 12, 11, 14], borderColor: '#3498db', tension: 0.3 }]
        }
    });
</script>
</body>
</html>
