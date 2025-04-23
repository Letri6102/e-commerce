<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/OrderService.php';

$db = new Database();
$model = new OrderService($db->conn);
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
            if (isset($data['TrangThai'])) $TrangThai = $data['TrangThai'];
            else $TrangThai = '';
            if ($TrangThai != 'Chờ xác nhận' && $TrangThai != 'Đã xác nhận' && $TrangThai != 'Đang vận chuyển' && $TrangThai != 'Đã giao hàng' && $TrangThai != 'Đã hủy' && $TrangThai != '') {
                echo json_encode(['success'=> false, 'message'=> 'Thông tin trạng thái không hợp lệ']);
                return;
            }
            if (isset($data['ThanhToan'])) $ThanhToan = $data['ThanhToan'];
            else $ThanhToan = '';
            if ($ThanhToan != 'Chưa thanh toán' && $ThanhToan != 'Đã thanh toán' && $ThanhToan != 'Huỷ thanh toán' && $ThanhToan != '') {
                echo json_encode(['success'=> false, 'message'=> 'Trạng thái thanh toán không hợp lệ']);
                return;
            }
            if (isset($data['ID_DonHang'])) $ID_DonHang = $data['ID_DonHang'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            echo json_encode($model->update($TrangThai, $ThanhToan, $ID_DonHang));
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