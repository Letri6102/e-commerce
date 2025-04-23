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
            if (isset($_POST['MaTinTuc'])) $MaTinTuc = $_POST['MaTinTuc'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            $TieuDe = isset($_POST['TieuDe']) ? $_POST['TieuDe'] : null;
            $MoTaTinTuc = isset($_POST['MoTaTinTuc']) ? $_POST['MoTaTinTuc'] : null;
            $NoiDung = isset($_POST['NoiDung']) ? $_POST['NoiDung'] : null;
            $TuKhoa = isset($_POST['TuKhoa']) ? $_POST['TuKhoa'] : null;
            $TrangThai = isset($_POST['TrangThai']) ? $_POST['TrangThai'] : null;
            $MoTaHinhAnh = isset($_POST['MoTaHinhAnh']) ? $_POST['MoTaHinhAnh'] : [];
            $AnhMuonXoa = isset($_POST['AnhMuonXoa']) ? $_POST['AnhMuonXoa'] : [];

            $news = $model->updateNews($TieuDe, $MoTaTinTuc, $NoiDung, $TuKhoa, $MaTinTuc, $TrangThai);
            $image = $model->updateNewsImage($AnhMuonXoa, $MoTaHinhAnh, $MaTinTuc);
            if ($news && $image) echo json_encode(['success' => true, 'message' => 'Cập nhật tin tức thành công']);
            else echo json_encode(['success' => false, 'message' => 'Cập nhật tin tức thất bại']);
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