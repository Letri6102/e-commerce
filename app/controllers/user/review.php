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
    if (isset($data['SoSao'])) $SoSao = $data['SoSao'];
    else $SoSao = '';
    if (isset($data['NoiDung'])) $NoiDung = $data['NoiDung'];
    else $NoiDung = '';
    echo json_encode($model->setReview($_SESSION["uid"], $ID_SP, $SoSao, $NoiDung));
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>