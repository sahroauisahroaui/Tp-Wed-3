CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'professor', 'student')
);

CREATE TABLE semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50),
    academic_year INT,
    is_active TINYINT(1) DEFAULT 0
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    credits INT,
    semester_id INT,
    FOREIGN KEY (semester_id) REFERENCES semesters(id)
);

CREATE TABLE grades (
    student_id INT,
    course_id INT,
    semester_id INT,
    professor_id INT,
    grade DECIMAL(3,2),
    PRIMARY KEY (student_id, course_id, semester_id)
);
