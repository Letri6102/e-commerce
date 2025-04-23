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
            $LienKet = isset($_POST['LienKet']) ? $_POST['LienKet'] : null;
            
            $news = $model->setSocial($LienKet);
            if ($news['success'] == true) {
                $MaMXH = $news['social_id'];
            }
            else {
                echo json_encode(['success' => false, 'message' => $product['message']]);
                return;
            }
            $uploadDir = dirname(__DIR__, 3) . "/public/image/social/$MaMXH/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploaded = false;

            if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $newsTmpPath = $_FILES['file']['tmp_name'];
                $newsFileName = time() . '_' . uniqid() . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); 

                $newsPath = $uploadDir . $newsFileName;

                if (move_uploaded_file($newsTmpPath, $newsPath)) {
                    $result = $model->updateSocial($MaMXH, "/public/image/social/$MaMXH/" . $newsFileName, null, null);
                    $uploaded = true;
                }

                if (!$uploaded) {
                    echo json_encode(['success' => false, 'message' => 'Không thể tải hình ảnh mạng xã hội']);
                } else {
                    if ($result) echo json_encode(['success' => true, 'message' => 'Thêm mạng xã hội thành công']);
                    else echo json_encode(['success' => false, 'message' => 'Thêm mạng xã hội thất bại']);
                }
            }
            else echo json_encode(['success' => true, 'message' => 'Thêm mạng xã hội thành công']);
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