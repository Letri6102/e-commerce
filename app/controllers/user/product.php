<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/UserService.php';

$db = new Database();
$model = new UserService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    if (!isset($_SESSION["uid"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        return;
    }
    $product = $model->getProduct();
    echo json_encode($product);
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>