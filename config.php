<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "my_gpa_system"; // الاسم الذي اخترتِيه في phpMyAdmin

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>

