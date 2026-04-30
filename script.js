document.addEventListener('DOMContentLoaded', function () {
    // Initialize Chart.js
    const ctx = document.getElementById('myChart').getContext('2d');
    
    const gpaData = {
        labels: [], // Initial empty labels
        datasets: [{
            label: 'GPA Progress',
            data: [], // Initial empty data
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3498db',
            pointRadius: 5,
            fill: true
        }]
    };

    const myChart = new Chart(ctx, {
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
                    max: 4.0
                }
            }
        }
    });

    // Step 6: AJAX function to fetch student history
    window.loadStudentHistory = function(studentId) {
        fetch(`get_history.php?id=${studentId}`)
            .then(response => response.json())
            .then(data => {
                myChart.data.labels = data.map(item => item.semester_label);
                myChart.data.datasets[0].data = data.map(item => item.gpa);
                myChart.update();
            })
            .catch(error => console.error('History Fetch Error:', error));
    };

    // Step 9: Filter function for student search
    window.filterStudents = function() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let rows = document.querySelectorAll('#studentList tr');
        
        rows.forEach(row => {
            let name = row.cells[0].textContent.toLowerCase();
            row.style.display = name.includes(input) ? '' : 'none';
        });
    };

    // Save grades function
    window.saveGrades = function() {
        const inputs = document.querySelectorAll('.grade-input');
        let gradesData = [];
        
        inputs.forEach(input => {
            gradesData.push({
                student_id: input.dataset.student,
                grade: input.value
            });
        });

        fetch('process_grades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(gradesData)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert("Grades updated successfully");
            }
        })
        .catch(error => console.error('Save Error:', error));
    };
});

