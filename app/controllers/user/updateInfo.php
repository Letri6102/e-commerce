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
    if (isset($data['name'])) $name = $data['name'];
    else $name = '';
    if (isset($data['sex'])) $sex = $data['sex'];
    else $sex = '';
    if (isset($data['phone'])) $phone = $data['phone'];
    else $phone = '';
    if (strlen($phone) < 10) {
        echo json_encode(['success' => false,'message' => 'Số điện thoại phải có 10 chữ số']);
        return;
    }
    if (isset($data['address'])) $addr = $data['address'];
    else $addr = '';
    echo json_encode($model->setInfo($_SESSION["uid"], $sex, $phone, $addr, $name));
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>