<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/UserService.php';

$db = new Database();
$model = new UserService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    echo json_encode([
        'danh_sach_banner' => $model->getBanner(),
        'danh_sach_san_pham' => $model->getProduct(),
        'danh_sach_phan_loai' => $model->getProductList()
    ]);
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
