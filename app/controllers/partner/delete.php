<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/SystemService.php';

$db = new Database();
$model = new SystemService($db->conn);
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
            if (isset($data['MaDoiTac'])) $MaDoiTac = $data['MaDoiTac'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            $model->deleteImagePartner($MaDoiTac);
            $result = $model->deletePartner($MaDoiTac);
            if ($result) echo json_encode(['success' => true,'message' => 'Xóa đối tác thành công']);
            else echo json_encode(['success' => false, 'message' => 'Xóa đối tác thất bại']);
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