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
            if (isset($data['TrangThai'])) $TrangThai = $data['TrangThai'];
            else echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
            if (isset($data['GhiChu'])) $GhiChu = $data['GhiChu'];
            else $GhiChu = '';
            if (isset($data['MaDeXuat'])) $MaDeXuat = $data['MaDeXuat'];
            else echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
            echo json_encode($model->updatePropose($TrangThai, $GhiChu, $MaDeXuat));
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