<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/OrderService.php';

$db = new Database();
$model = new OrderService($db->conn);
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    if (!isset($_SESSION["uid"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        return;
    }
    $paid = $model->getPaid($_SESSION["uid"]);
    if ($paid['success'] === true) {
        echo json_encode(['success' => true, 'message' => $paid['message']]);
    }
    else echo json_encode(['success' => false, 'message' => $paid['message']]);
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>