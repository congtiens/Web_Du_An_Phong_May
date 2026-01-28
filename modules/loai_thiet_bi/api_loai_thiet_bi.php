<?php
// File: api_loai_thiet_bi.php
require '../../config/db.php';

$action = $_POST['action'] ?? '';

/* 1. LẤY DANH SÁCH (FETCH) */
if ($action == 'fetch') {
    // Sửa thành bảng loai_thiet_bi
    $rs = $conn->query("SELECT * FROM loai_thiet_bi ORDER BY id_loai ASC");
    $data = [];
    while ($row = $rs->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

/* 2. THÊM MỚI (INSERT) */
if ($action == 'insert') {
    $ten = $_POST['ten'];
    $moTa = $_POST['moTa'];

    // Sửa câu lệnh INSERT
    $stmt = $conn->prepare("INSERT INTO loai_thiet_bi(ten_loai, mo_ta) VALUES (?,?)");
    $stmt->bind_param("ss", $ten, $moTa);
    echo $stmt->execute();
}

/* 3. CẬP NHẬT (UPDATE) */
if ($action == 'update') {
    $id = $_POST['id'];
    $ten = $_POST['ten'];
    $moTa = $_POST['moTa'];

    // Sửa câu lệnh UPDATE, chú ý id_loai
    $stmt = $conn->prepare("UPDATE loai_thiet_bi SET ten_loai=?, mo_ta=? WHERE id_loai=?");
    $stmt->bind_param("ssi", $ten, $moTa, $id);
    echo $stmt->execute();
}

/* 4. XÓA (DELETE) */
if ($action == 'delete') {
    $id = $_POST['id'];
    // Sửa câu lệnh DELETE
    $stmt = $conn->prepare("DELETE FROM loai_thiet_bi WHERE id_loai=?");
    $stmt->bind_param("i", $id);
    echo $stmt->execute();
}
?>