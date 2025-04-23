<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AdminService.php';

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
            if (isset($data['MaDanhGia'])) $MaDanhGia = $data['MaDanhGia'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            if (isset($data['TrangThai'])) $TrangThai = $data['TrangThai'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            if ($TrangThai != 'Đang ẩn' && $TrangThai != 'Đang hiện' && $TrangThai != 'Đã xóa') {
                echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ!']);
                return;
            }
            if (isset($data['PhanHoi'])) $PhanHoi = $data['PhanHoi'];
            else $PhanHoi = '';
            echo json_encode($model->statusReview($MaDanhGia, $TrangThai, $PhanHoi));
        }
        else echo json_encode(['success' => false, 'message' => 'Người dùng không có quyền truy cập']);
    }
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>