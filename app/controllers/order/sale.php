<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/OrderService.php';

$db = new Database();
$model = new OrderService($db->conn);
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["uid"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        return;
    }
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['MaGiamGia'])) $MaGiamGia = $data['MaGiamGia'];
    else $MaGiamGia = '';
    if (isset($data['tienHang'])) $tienHang = $data['tienHang'];
    else $tienHang = 0;
    if (isset($data['phiVanChuyen'])) $phiVanChuyen = $data['phiVanChuyen'];
    else $phiVanChuyen = 0;
    if (isset($data['PhuongThucThanhToan'])) $PhuongThucThanhToan = $data['PhuongThucThanhToan'];
    else $PhuongThucThanhToan = 0;
    $money = $model->sale($tienHang, $MaGiamGia, $phiVanChuyen, $PhuongThucThanhToan);
    echo json_encode($money);
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>