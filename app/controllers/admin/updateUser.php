<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AdminService.php';

$db = new Database();
$model = new AdminService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["email"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
    }
    else {
        if ($_SESSION["Role"] == 'Admin') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            if (isset($data['UID'])) $UID = $data['UID'];
            else $UID = '';
            if (isset($data['name'])) $name = $data['name'];
            else $name = '';
            if (isset($data['email'])) $email = $data['email'];
            else $email = '';
            if (isset($data['phone'])) $phone = $data['phone'];
            else $phone = '';
            if (isset($data['sex'])) $sex = $data['sex'];
            else $sex = '';
            if (isset($data['status'])) $status = $data['status'];
            else $status = '';
            if (isset($data['address'])) $addr = $data['address'];
            else $addr = '';
            echo json_encode($model->updateUser($UID, $name, $email, $phone, $sex, $addr, $status)); 
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