<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "Tp_Web_3"; // الاسم الذي اخترتِيه في phpMyAdmin

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>

