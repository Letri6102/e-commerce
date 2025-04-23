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
            $Loai = isset($_POST['Loai']) ? $_POST['Loai'] : null;
            $ThongTin = isset($_POST['ThongTin']) ? $_POST['ThongTin'] : null;
            
            $news = $model->setContact($Loai, $ThongTin);
            if ($news['success'] == true) {
                $MaContact = $news['contact_id'];
            }
            else {
                echo json_encode(['success' => false, 'message' => $product['message']]);
                return;
            }
            $uploadDir = dirname(__DIR__, 3) . "/public/image/contact/$MaContact/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploaded = false;

            if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $newsTmpPath = $_FILES['file']['tmp_name'];
                $newsFileName = time() . '_' . uniqid() . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); 

                $newsPath = $uploadDir . $newsFileName;

                if (move_uploaded_file($newsTmpPath, $newsPath)) {
                    $result = $model->updateContact($MaContact, "/public/image/contact/$MaContact/" . $newsFileName, null, null, null);
                    $uploaded = true;
                }

                if (!$uploaded) {
                    echo json_encode(['success' => false, 'message' => 'Không thể tải ảnh thông tin liên hệ']);
                } else {
                    if ($result) echo json_encode(['success' => true, 'message' => 'Thêm thông tin liên hệ thành công']);
                    else echo json_encode(['success' => false, 'message' => 'Thêm thông tin liên hệ thất bại']);
                }
            }
            else echo json_encode(['success' => true, 'message' => 'Thêm thông tin liên hệ thành công']);
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