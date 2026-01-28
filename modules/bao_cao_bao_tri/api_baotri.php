<?php
// File: api_baotri.php
require '../../config/db.php'; // Đảm bảo đường dẫn đúng

header('Content-Type: application/json');
$action = $_POST['action'] ?? '';

// 1. LẤY DANH SÁCH BÁO CÁO (KÈM TÊN NGƯỜI VÀ MÁY)
if ($action == 'fetch') {
    // Kỹ thuật JOIN bảng để lấy tên thay vì lấy ID vô hồn
    $sql = "SELECT 
                bc.id_bao_cao, 
                bc.mo_ta_loi, 
                bc.trang_thai_bao_cao, 
                bc.phan_hoi_admin, 
                bc.ngay_bao,
                nd.ten_dang_nhap, -- Lấy tên người dùng
                tb.ten_thiet_bi,   -- Lấy tên máy
                tb.ma_thiet_bi
            FROM bao_cao_bao_tri bc
            JOIN nguoi_dung nd ON bc.id_nguoi_dung = nd.id_nguoi_dung
            JOIN thiet_bi tb ON bc.id_thiet_bi = tb.id_thiet_bi
            ORDER BY bc.ngay_bao ASC"; // Mới nhất lên đầu
            
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

// 2. LẤY DỮ LIỆU CHO Ô CHỌN (DROPDOWN)
if ($action == 'load_options') {
    $users = $conn->query("SELECT id_nguoi_dung, ten_dang_nhap FROM nguoi_dung")->fetch_all(MYSQLI_ASSOC);
    $devices = $conn->query("SELECT id_thiet_bi, ten_thiet_bi, ma_thiet_bi FROM thiet_bi")->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode(['users' => $users, 'devices' => $devices]);
}

// 3. THÊM BÁO CÁO MỚI
if ($action == 'insert') {
    $id_nguoi = $_POST['id_nguoi_dung'];
    $id_may = $_POST['id_thiet_bi'];
    $loi = $_POST['mo_ta_loi'];
    
    // Mặc định trạng thái là 'Cho_Duyet', ngày báo là hiện tại (NOW())
    $stmt = $conn->prepare("INSERT INTO bao_cao_bao_tri (id_nguoi_dung, id_thiet_bi, mo_ta_loi, trang_thai_bao_cao, ngay_bao) VALUES (?, ?, ?, 'Cho_Duyet', NOW())");
    $stmt->bind_param("iis", $id_nguoi, $id_may, $loi);
    echo $stmt->execute();
}

// 4. CẬP NHẬT TRẠNG THÁI (Dành cho Admin duyệt/sửa)
if ($action == 'update') {
    $id = $_POST['id'];
    $status = $_POST['trang_thai'];
    $phan_hoi = $_POST['phan_hoi'];
    
    $stmt = $conn->prepare("UPDATE bao_cao_bao_tri SET trang_thai_bao_cao=?, phan_hoi_admin=?, ngay_giai_quyet=NOW() WHERE id_bao_cao=?");
    $stmt->bind_param("ssi", $status, $phan_hoi, $id);
    echo $stmt->execute();
}

// 5. XÓA BÁO CÁO
if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM bao_cao_bao_tri WHERE id_bao_cao=?");
    $stmt->bind_param("i", $id);
    echo $stmt->execute();
}
?>