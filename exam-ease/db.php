<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'exam_app';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);
$conn->query("CREATE TABLE IF NOT EXISTS papers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    year YEAR NOT NULL,
    filename VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
?>