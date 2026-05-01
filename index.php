<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>لوحة التحكم - إدارة العلامات</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-7 card p-3 shadow-sm">
                <h4>رصد علامات الطلاب</h4>
                <table class="table">
                    <thead>
                        <tr><th>اسم الطالب</th><th>الدرجة (4.0)</th></tr>
                    </thead>
                    <tbody id="studentList">
                        <?php
                        // جلب البيانات مع ربط جدول المستخدمين لجلب الأسماء
                        $sql = "SELECT g.grade, u.name FROM grades g 
                                JOIN users u ON g.student_id = u.id";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['name']) . "</td>
                                        <td><input type='text' class='grade-input' value='" . $row['grade'] . "' readonly></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center'>لا توجد بيانات، استخدم صفحة الإضافة أولاً.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add_grade.php" class="btn btn-success mt-3">إضافة علامة جديدة</a>
            </div>

            <div class="col-md-4 offset-md-1 card p-3 shadow-sm">
                <h4>تطور المعدل التراكمي</h4>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // تمرير البيانات من PHP إلى JavaScript للرسم البياني
        <?php
        $chart_data = [];
        $labels = [];
        $res = $conn->query("SELECT grade FROM grades ORDER BY student_id ASC LIMIT 5");
        $count = 1;
        while($row = $res->fetch_assoc()) {
            $chart_data[] = $row['grade'];
            $labels[] = "نقطة " . $count++;
        }
        ?>

        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'المعدل',
                    data: <?php echo json_encode($chart_data); ?>,
                    borderColor: '#3498db',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(52, 152, 219, 0.1)'
                }]
            },
            options: { responsive: true, scales: { y: { min: 0, max: 4 } } }
        });
    </script>
</body>
</html>
