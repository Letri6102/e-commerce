<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/CartService.php';

$db = new Database();
$model = new CartService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["email"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
    }
    else {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (isset($data['ID_SP'])) $ID_SP = $data['ID_SP'];
        else $ID_SP = '';
        if (isset($data['quantity'])) $quantity = $data['quantity'];
        else $quantity = '';
        echo json_encode($model->update($_SESSION["uid"], $ID_SP, $quantity));
    }
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>