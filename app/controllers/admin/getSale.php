<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AdminService.php';
require_once dirname(__DIR__, 2) . '/models/ProductService.php';

$db = new Database();
$model = new AdminService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    if (!isset($_SESSION["email"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
    }
    else {
        if ($_SESSION["Role"] == 'Admin') {
            $sale_list = $model->sale();
            $condition = $model->conditionSale();
            echo json_encode([
                'success' => true,
                'danh_sach_khuyen_mai' => $sale_list,
                'danh_sach_dieu_kien' => $condition
            ]);
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