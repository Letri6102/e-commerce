<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AdminService.php';
require_once dirname(__DIR__, 2) . '/models/ProductService.php';

$db = new Database();
$model = new AdminService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["email"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
    }
    else {
        if ($_SESSION["Role"] == 'Admin') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            if (isset($data['ID_GiamGia'])) $ID_GiamGia = $data['ID_GiamGia'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            if (isset($data['Ma'])) $Ma = $data['Ma'];
            else $Ma = '';
            if (isset($data['TienGiam'])) $TienGiam = $data['TienGiam'];
            else $TienGiam = -1;
            if (isset($data['DieuKien'])) $DieuKien = $data['DieuKien'];
            else $DieuKien = '';
            if (isset($data['SoLuong'])) $SoLuong = $data['SoLuong'];
            else $SoLuong = -1;
            if (isset($data['TrangThai'])) $TrangThai = $data['TrangThai'];
            else $TrangThai = '';
            echo json_encode($model->updateSale($ID_GiamGia, $Ma, $TienGiam, $DieuKien, $SoLuong, $TrangThai));
        }
        else echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    }
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>