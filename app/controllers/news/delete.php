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
            if (isset($data['MaTinTuc'])) $MaTinTuc = $data['MaTinTuc'];
            else {
                echo json_encode(['success' => false, 'message' => 'Chưa điền đầy đủ thông tin']);
                return;
            }
            $image = $model->deleteImageNews($MaTinTuc);
            $news = $model->deleteNews($MaTinTuc);
            $deleteDir = dirname(__DIR__, 3) . "/public/image/new/$MaTinTuc";
            if (is_dir($deleteDir)) {
                $files = array_diff(scandir($deleteDir), ['.', '..']);
                foreach ($files as $file) {
                    $filePath = $deleteDir . DIRECTORY_SEPARATOR . $file;
                    unlink($filePath);
                }
                rmdir($deleteDir);
            }
            if ($news && $image) echo json_encode(['success' => true, 'message' => 'Xóa tin tức thành công']);
            else echo json_encode(['success' => false, 'message' => 'Xóa tin tức thất bại']);
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