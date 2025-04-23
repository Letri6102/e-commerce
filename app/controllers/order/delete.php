<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/OrderService.php';

$db = new Database();
$model = new OrderService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["email"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
    }
    else {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (isset($data['ID_DonHang'])) $ID_DonHang = $data['ID_DonHang'];
        else {
            echo json_encode('Chưa điền đầy đủ thông tin');
            return;
        }
        echo json_encode($model->delete($_SESSION["uid"], $ID_DonHang));
    }
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>