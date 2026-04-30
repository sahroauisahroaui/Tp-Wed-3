document.addEventListener('DOMContentLoaded', function () {
    // 1. إعداد الرسم البياني (GPA Chart)
    const ctx = document.getElementById('myChart').getContext('2d');
    
    // بيانات تجريبية لمحاكاة الصورة
    const gpaData = {
        labels: ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5'],
        datasets: [{
            label: 'GPA Progress',
            data: [3.1, 3.4, 3.2, 3.8, 3.6], // هذه الأرقام هي التي ترسم المنحنى
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 3,
            tension: 0.4, // يجعل الخط متعرجاً وانسيابياً كالصورة
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3498db',
            pointRadius: 5,
            fill: true
        }]
    };

    const config = {
        type: 'line',
        data: gpaData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0,
                    max: 4.0,
                    grid: { display: false }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    };

    const myChart = new Chart(ctx, config);

    // 2. وظيفة حفظ الدرجات (Simulated)
    window.saveGrades = function() {
        const inputs = document.querySelectorAll('.grade-input');
        let grades = [];
        inputs.forEach(input => {
            grades.push({
                id: input.dataset.student,
                val: input.value
            });
        });
        
        console.log("Saving grades to database...", grades);
        alert("تم حفظ الدرجات وتحديث المعدلات بنجاح!");
    };
});

