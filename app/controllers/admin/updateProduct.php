<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AdminService.php';
require_once dirname(__DIR__, 2) . '/models/support.php';

$db = new Database();
$model = new AdminService($db->conn);
$support = new support();
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["email"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
    }
    else {
        if ($_SESSION["Role"] == 'Admin') {
            // Xử lí thông tin sản phẩm
            $ID_SP = isset($_POST['ID_SP']) ? $_POST['ID_SP'] : '';
            $TenSp = isset($_POST['TenSp']) ? $_POST['TenSp'] : '';
            $MoTa = isset($_POST['MoTa']) ? $_POST['MoTa'] : '';
            $Gia = isset($_POST['Gia']) ? $_POST['Gia'] : '';
            $TyLeGiamGia = isset($_POST['TyLeGiamGia']) ? $_POST['TyLeGiamGia'] : '';
            $SoLuongKho = isset($_POST['SoLuongKho']) ? $_POST['SoLuongKho'] : '';
            $NXB = isset($_POST['NXB']) ? $_POST['NXB'] : '';
            $KichThuoc = isset($_POST['KichThuoc']) ? $_POST['KichThuoc'] : '';
            $SoTrang = isset($_POST['SoTrang']) ? $_POST['SoTrang'] : '';
            $PhanLoai = isset($_POST['PhanLoai']) ? $_POST['PhanLoai'] : '';
            $TuKhoa = isset($_POST['TuKhoa']) ? $_POST['TuKhoa'] : '';
            $HinhThuc = isset($_POST['HinhThuc']) ? $_POST['HinhThuc'] : '';
            $TacGia = isset($_POST['TacGia']) ? $_POST['TacGia'] : '';
            $NgonNgu = isset($_POST['NgonNgu']) ? $_POST['NgonNgu'] : '';
            $NamXB = isset($_POST['NamXB']) ? $_POST['NamXB'] : '';

            // Xử lí hình ảnh sản phẩm
            $product = $model->updateProductInfo($ID_SP, $TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB);
            $uploadDir = dirname(__DIR__, 3) . "/public/image/product/$ID_SP/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            else {
                $support->deleteDirectory($uploadDir);
                mkdir($uploadDir, 0777, true);
            }
            $uploaded = false;
            foreach ($_FILES as $uploadFile) {
                // Check if multiple files are uploaded
                if (is_array($uploadFile['error'])) {
                    // Multiple files
                    foreach ($uploadFile['error'] as $i => $error) { 
                        if ($error == UPLOAD_ERR_OK) {
                            $avatarTmpPath = $uploadFile['tmp_name'][$i];
                            $avatarFileName = time() . '_' . uniqid() . '.' . pathinfo($uploadFile['name'][$i], PATHINFO_EXTENSION); 
                            $avatarPath = $uploadDir . $avatarFileName;

                            if (move_uploaded_file($avatarTmpPath, $avatarPath)) {
                                $result = $model->updateProductImage($avatarPath, $ID_SP);
                                $uploaded = true;
                            }
                        }
                    }
                } else {
                    // Single file
                    if ($uploadFile['error'] == UPLOAD_ERR_OK) {
                        $avatarTmpPath = $uploadFile['tmp_name'];
                        $avatarFileName = time() . '_' . uniqid() . '.' . pathinfo($uploadFile['name'], PATHINFO_EXTENSION); 
                        $avatarPath = $uploadDir . $avatarFileName;

                        if (move_uploaded_file($avatarTmpPath, $avatarPath)) {
                            $result = $model->updateProductImage($avatarPath, $ID_SP);
                            $uploaded = true;
                        }
                    }
                }
            }

            if (!$uploaded) {
                echo json_encode(['success' => false, 'message' => 'Không thể tải ảnh sản phẩm']);
            } else {
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