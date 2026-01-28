<?php
// File: api_thiet_bi.php
// Lưu ý: Kiểm tra đường dẫn file db.php cho đúng với thư mục bạn đang đứng
require '../../config/db.php'; 

$action = $_POST['action'] ?? '';

/* 1. LẤY DANH SÁCH (FETCH) */
if ($action == 'fetch') {
    // Chọn tất cả thiết bị, sắp xếp mới nhất lên đầu
    $sql = "SELECT * FROM thiet_bi ORDER BY id_thiet_bi ASC";
    $rs = $conn->query($sql);
    
    $data = [];
    while ($row = $rs->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

/* 2. THÊM MỚI (INSERT) */
if ($action == 'insert') {
    $ma = $_POST['ma'];
    $ten = $_POST['ten'];
    $ts = $_POST['thongSo'];
    $phong = $_POST['idPhong']; // Sau này nên làm Dropdown chọn phòng
    $loai = $_POST['idLoai'];   // Sau này nên làm Dropdown chọn loại
    $tt = $_POST['trangThai'];

    $stmt = $conn->prepare("INSERT INTO thiet_bi(ma_thiet_bi, ten_thiet_bi, thong_so_ky_thuat, id_phong, id_loai, trang_thai) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiis", $ma, $ten, $ts, $phong, $loai, $tt);
    
    echo $stmt->execute(); // Trả về true/false
}

/* 3. CẬP NHẬT (UPDATE) */
if ($action == 'update') {
    $id = $_POST['id'];
    $ma = $_POST['ma'];
    $ten = $_POST['ten'];
    $ts = $_POST['thongSo'];
    $phong = $_POST['idPhong'];
    $loai = $_POST['idLoai'];
    $tt = $_POST['trangThai'];

    $stmt = $conn->prepare("UPDATE thiet_bi SET ma_thiet_bi=?, ten_thiet_bi=?, thong_so_ky_thuat=?, id_phong=?, id_loai=?, trang_thai=? WHERE id_thiet_bi=?");
    $stmt->bind_param("sssissi", $ma, $ten, $ts, $phong, $loai, $tt, $id);
    
    echo $stmt->execute();
}

/* 4. XÓA (DELETE) */
if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM thiet_bi WHERE id_thiet_bi=?");
    $stmt->bind_param("i", $id);
    echo $stmt->execute();
}
?>