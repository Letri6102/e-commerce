<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/UserService.php';

$db = new Database();
$model = new UserService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["uid"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        return;
    }
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['ID_SP'])) $ID_SP = $data['ID_SP'];
    else $ID_SP = '';
    echo json_encode($model->unlike($_SESSION["uid"], $ID_SP));
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>