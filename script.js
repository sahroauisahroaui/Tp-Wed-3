document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('myChart').getContext('2d');
    
    // إعداد الرسم البياني
    const gpaData = {
        labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
        datasets: [{
            label: 'تطور المعدل',
            data: [3.2, 3.5, 3.1, 3.8], 
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: gpaData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { min: 0, max: 4.0 } }
        }
    });

    // وظيفة حفظ الدرجات وإرسالها للسيرفر
    window.saveGrades = function() {
        const inputs = document.querySelectorAll('.grade-input');
        let gradesData = [];
        
        inputs.forEach(input => {
            gradesData.push({
                student_id: input.dataset.student,
                grade: input.value
            });
        });

        // إرسال البيانات إلى ملف التوجيه (Controller)
        fetch('process_grades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(gradesData)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert("تم تحديث المعدلات في قاعدة البيانات بنجاح!");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("حدث خطأ أثناء الاتصال بالسيرفر.");
        });
    };
});
