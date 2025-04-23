<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/SystemService.php';

$db = new Database();
$model = new SystemService($db->conn);
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
            if (isset($data['MaHeThong'])) $MaHeThong = $data['MaHeThong'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            if (isset($data['TrangThaiBaoTri']) && ($data['TrangThaiBaoTri'] == 0 || $data['TrangThaiBaoTri'] == 1)) $TrangThaiBaoTri = $data['TrangThaiBaoTri'];
            else $TrangThaiBaoTri = -1;
            if (isset($data['TuKhoa'])) $TuKhoa = $data['TuKhoa'];
            else $TuKhoa = null;
            if (isset($data['ClientID'])) $ClientID = $data['ClientID'];
            else $ClientID = null;
            if (isset($data['APIKey'])) $APIKey = $data['APIKey'];
            else $APIKey = null;
            if (isset($data['Checksum'])) $Checksum = $data['Checksum'];
            else $Checksum = null;
            
            $news = $model->updateSystem($MaHeThong, $TuKhoa, $ClientID, $APIKey, $Checksum, $TrangThaiBaoTri);
            if ($news == true) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật hệ thống thành công']);
            }
            else echo json_encode(['success' => false, 'message' => 'Cập nhật hệ thống thất bại']);
        }
        else echo json_encode(['success' => false, 'message' => 'không có quyền truy cập']);
    }
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>