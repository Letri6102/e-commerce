<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/ProductService.php';

$db = new Database();
$model = new ProductService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["uid"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        return;
    }
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['TenSP'])) $TenSP = $data['TenSP'];
    else $TenSP = '';
    if (isset($data['NoiDung'])) $NoiDung = $data['NoiDung'];
    else $NoiDung = '';
    if (isset($data['GhiChu'])) $GhiChu = $data['GhiChu'];
    else $GhiChu = '';
    echo json_encode($model->proposeProduct($TenSP, $NoiDung, $_SESSION["uid"], $GhiChu));
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>