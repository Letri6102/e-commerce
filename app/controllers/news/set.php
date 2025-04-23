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
            // Xử lí thông tin tin tức
            $TieuDe = isset($_POST['TieuDe']) ? $_POST['TieuDe'] : null;
            $MoTaTinTuc = isset($_POST['MoTaTinTuc']) ? $_POST['MoTaTinTuc'] : null;
            $NoiDung = isset($_POST['NoiDung']) ? $_POST['NoiDung'] : null;
            $TuKhoa = isset($_POST['TuKhoa']) ? $_POST['TuKhoa'] : null;
            $MoTaHinhAnh = isset($_POST['MoTaHinhAnh']) ? $_POST['MoTaHinhAnh'] : null;

            // Xử lí hình ảnh tin tức
            $news = $model->setNews($TieuDe, $MoTaTinTuc, $NoiDung, $TuKhoa);
            if ($news['success'] == true) {
                $MaTinTuc = $news['news_id'];
            }
            else {
                echo json_encode(['success' => false, 'message' => $product['message']]);
                return;
            }
            $uploadDir = dirname(__DIR__, 3) . "/public/image/new/$MaTinTuc/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploaded = false;
            if (isset($_FILES) && $_FILES != []) {
                foreach ($_FILES as $uploadFile) {
                    $count = count($uploadFile['error']);
                    for ($i = 0; $i < $count; $i++) { 
                        if ($uploadFile['error'][$i] == UPLOAD_ERR_OK) {
                            $newsTmpPath = $uploadFile['tmp_name'][$i];
                            $newsFileName = time() . '_' . uniqid() . '.' . pathinfo($uploadFile['name'][$i], PATHINFO_EXTENSION); 
    
                            $newsPath = $uploadDir . $newsFileName;
    
                            if (move_uploaded_file($newsTmpPath, $newsPath)) {
                                $result = $model->setImageNews("/public/image/new/$MaTinTuc/" . $newsFileName, $MaTinTuc, $MoTaHinhAnh[$i]);
                                $uploaded = true;
                            }
                        }
                    }
                }
                if (!$uploaded) {
                    echo json_encode(['success' => false, 'message' => 'Không thể tải ảnh tin tức']);
                }
                else echo json_encode($result);
            }
            else {
                $result = $model->setImageNews(null, $MaTinTuc, $MoTaHinhAnh);
                echo json_encode($result);
            }
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