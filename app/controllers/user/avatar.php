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
    $id = $_SESSION["uid"];
    $uploaded = false;
    foreach ($_FILES as $uploadFile) {
        if ($uploadFile['error'] === UPLOAD_ERR_OK) {
            $avatarTmpPath = $uploadFile['tmp_name'];
            $avatarFileName = time() . '.' . pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
            $uploadDir = dirname(__DIR__, 3) . "/public/image/user/$id/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            else {
                $files = glob($uploadDir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }

            $avatarPath = $uploadDir . $avatarFileName;

            if (move_uploaded_file($avatarTmpPath, $avatarPath)) {
                $result = $model->setAvatar($id, $avatarPath);
                $img = $result['image'];
                $_SESSION['Avatar'] = $img;
                echo json_encode(value: $result);
                $uploaded = true;
                break;
            }
        }
    }

    if (!$uploaded) {
        echo json_encode(['success' => false, 'message' => 'Không thể lưu ảnh đại diện']);
    }
    // else echo json_encode(['success'=> true,'message'=> 'Cập nhật ảnh đại diện thành công']);
} else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>