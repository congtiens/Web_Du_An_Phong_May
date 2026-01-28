<?php
// File: db.php
$conn = new mysqli("localhost", "root", "", "ql_phongmay"); // Đổi tên DB ở đây
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
?>