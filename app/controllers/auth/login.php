<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AuthService.php';

$db = new Database();
$model = new AuthService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Người dùng đã đăng nhập']);
    }
    else {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (isset($data['email'])) $email = $data['email'];
        else $email = '';
        if (isset($data['password'])) $password = $data['password'];
        else $password = '';
        echo json_encode($model->login($email, $password));
    }
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
function isLoggedIn() {
    return isset($_SESSION["email"]);
}
?>
