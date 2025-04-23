<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AuthService.php';

$db = new Database();
$model = new AuthService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        return;
    }
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['current_password'])) $current_password = $data['current_password'];
    else $current_password = '';
    if (isset($data['new_password'])) $new_password = $data['new_password'];
    else $new_password = '';
    $email = $_SESSION["email"];
    $response = $model->changePassword($email, $current_password, $new_password);
    if ($response['status']) {
        echo json_encode([
            'success' => true,
            'message' => 'Thay đổi mật khẩu thành công',
            'user' => [
                'email' => $email,
                'password' => $response['password']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => $response['error']]);
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
