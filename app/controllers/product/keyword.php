<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/ProductService.php';

$db = new Database();
$model = new ProductService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['keyword'])) $keyword = $data['keyword'];
    else echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
    echo json_encode($model->keywork($keyword));
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>