<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام إدارة المعدل الأكاديمي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* التنسيق الذي أرسلتِه سابقاً ليعطي شكل البطاقات الاحترافية */
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
        .nav-pills .nav-link.active { background-color: #3498db; }
        .nav-link { color: #2c3e50; font-weight: 500; }
    </style>
</head>
<body>

<div class="container py-4">
    <h2 class="text-center mb-4">لوحة التحكم الأكاديمية</h2>

    <ul class="nav nav-pills nav-justified mb-4 card p-2 bg-white" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="tab-home" data-bs-toggle="pill" data-bs-target="#view-home">الرئيسية والإحصائيات</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-add" data-bs-toggle="pill" data-bs-target="#view-add">إدخال العلامات</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-history" data-bs-toggle="pill" data-bs-target="#view-history">سجل الطالب</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="view-home">
            <div class="row">
                <div class="col-md-8">
                    <div class="card p-4">
                        <h4>جدول العلامات الأخير</h4>
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr><th>الطالب</th><th>المادة</th><th>العلامة</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                include 'config.php';
                                $res = $conn->query("SELECT u.name as s_name, c.name as c_name, g.grade FROM grades g JOIN users u ON g.student_id = u.id JOIN courses c ON g.course_id = c.id LIMIT 5");
                                while($row = $res->fetch_assoc()) {
                                    echo "<tr><td>{$row['s_name']}</td><td>{$row['c_name']}</td><td><span class='badge bg-info text-dark'>{$row['grade']}</span></td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <h4>منحنى المعدل</h4>
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="view-add">
            <div class="card p-4 mx-auto" style="max-width: 600px;">
                <h4>إضافة علامة جديدة</h4>
                <form id="gradeForm">
                    <div class="mb-3"><label>رقم الطالب</label><input type="number" name="student_id" class="form-control" required></div>
                    <div class="mb-3"><label>رقم المادة</label><input type="number" name="course_id" class="form-control" required></div>
                    <div class="mb-3"><label>العلامة</label><input type="number" step="0.01" name="grade" class="form-control" required></div>
                    <button type="submit" class="btn btn-primary w-100">حفظ البيانات</button>
                </form>
                <div id="responseMessage" class="mt-3"></div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // كود الرسم البياني (كما في الخطوة 9)
    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['S1', 'S2', 'S3', 'S4'],
            datasets: [{
                label: 'تطور المعدل',
                data: [2.5, 3.2, 3.0, 3.7], // بيانات تجريبية
                borderColor: '#3498db',
                fill: true,
                backgroundColor: 'rgba(52, 152, 219, 0.1)'
            }]
        }
    });
</script>
</body>
</html>
