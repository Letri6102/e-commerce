<?php
require_once dirname(__DIR__, 1) . '/models/support.php';
require_once dirname(__DIR__, 1) . '/models/UserService.php';
require_once dirname(__DIR__, 1) . '/models/ProductService.php';


class AdminService {
    private $conn;
    private $support;
    private $user;
    private $product;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->support = new support();
        $this->user = new UserService($conn);
        $this->product = new ProductService($conn);
    }

    public function getUsers() {
        $sql = "SELECT `UID` FROM LOGIN WHERE ROLE = 'Customer' ORDER BY `UID` DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = $this->user->getUserInfo($row["UID"]);
            if ($user['success'] === false) {
                return ['success' => false, 'message' => $user['message']];
            }
            
            $login = $this->user->getLoginInfo($row["UID"]);
            if ($login['success'] === false) {
                return ['success' => false, 'message' => $login['message']];
            }

            if (!$user || !$login) {
                return ['success' => false, 'message' => 'Không tìm thấy người dùng'];
            }

            $users[] = [
                'uid' => $row['UID'],
                'name' => $login['name'],
                'role' => $login['role'],
                'email'=> $login['email'],
                'password' => $login['password'],
                'phone' => $user['phone'],
                'sex' => $user['sex'],
                'address' => $user['address'],
                'avatar' => $login['avatar'],
                'status' => $user['status']
            ];
        }
        return ['success' => true, 'user-list' => $users];
    }

    public function updateUser($UID, $name, $email, $phone, $sex, $addr, $status) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email không hợp lệ'];
        }
        $sql1 = "UPDATE LOGIN SET Ten = ?, Email = ? WHERE UID = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->bind_param("ssi", $name, $email, $UID);
        $stmt1->execute();
        
        if ($stmt1->affected_rows === -1) {
            return ["success" => false, "message" => "Error updating LOGIN table"];
        }
        
        $stmt1->close();

        // Second update statement
        $sql2 = "
        UPDATE KHACH_HANG SET
            GioiTinh = ?, SDT = ?, DiaChi = ?, TrangThai = ?
        WHERE UID = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("ssssi", $sex, $phone, $addr, $status, $UID);
        $stmt2->execute();
        if ($stmt2->affected_rows === -1) {
            return ["success" => false, "message" => "Error updating KHACH_HANG table"];
        }
        return ["success" => true, "message" => "Sửa thông tin thành công"];
    }

    public function product() {
        return ['success' => true, 'product_list' => $this->productInfo(), 'product_category' => $this->product->getType()];
    }

    public function addProductInfo($TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB) {
        $sql = "SELECT ID_SP FROM SAN_PHAM WHERE TenSp = ? AND NXB = ? AND NamXB = ? AND TacGia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssis", $TenSp, $NXB, $NamXB, $TacGia);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["success" => false, "message" => "Sản phẩm đã có trong kho"];
        }
        $sql = "INSERT INTO SAN_PHAM (TenSp, MoTa, Gia, TyLeGiamGia, SoLuongKho, NXB, KichThuoc, SoTrang, PhanLoai, TuKhoa, HinhThuc, TacGia, NgonNgu, NamXB) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssidississsssi",$TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB);
        $stmt->execute();
        $sql = "SELECT ID_SP FROM SAN_PHAM WHERE TenSp = ? AND NXB = ? AND NamXB = ? AND TacGia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssis", $TenSp, $NXB, $NamXB, $TacGia);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        return ["success" => true, "product_id" => $product['ID_SP']];
    }

    public function addProductImage ($Anh, $ID_SP) {
        $relativeAvatarPath = "public/image/product/$ID_SP/" . basename($Anh);
        $sql = "INSERT INTO HINH_ANH (Anh, ID_SP) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $relativeAvatarPath, $ID_SP);
        $stmt->execute();
        return ['success' => true, 'message' => 'Thêm sản phẩm thành công!'];
    }

    public function propose() {
        $propose_list = $this->proposeInfo();
        foreach ($propose_list as &$propose) {
            $user = $this->user->getUserInfo($propose["UID"]);
            if ($user['success'] === false) {
                return ['success' => false, 'message' => $user['message']];
            }
            
            $login = $this->user->getLoginInfo($propose["UID"]);
            if ($login['success'] === false) {
                return ['success' => false, 'message' => $login['message']];
            }

            if (!$user || !$login) {
                return ['success' => false, 'message' => 'Không tìm thấy người dùng'];
            }            
            $propose['ten'] = $login['name'];
            $propose['SDT'] = $user['phone'];
            $propose['email'] = $login['email'];
            $propose['gioi_tinh'] = $user['sex'];
        }
        return ['success'=> true, 'message'=> $propose_list];
    }

    public function updatePropose($status, $content, $id) {
        $sql = 'SELECT * FROM SAN_PHAM_DE_XUAT WHERE MaDeXuat = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ["success"=> false,"message"=> "Không tìm thấy đề xuất"];
        }
        $propose = $result->fetch_assoc();
        if ($status != 'Đang chờ duyệt' && $status != 'Đã duyệt' && $status != 'Đã từ chối') 
            return ['success'=> false, 'message' => 'Cập nhật thất bại'];
        $type = 'Yêu cầu';
        $date = $this->support->startTime();
        $sql = "INSERT INTO thong_bao (`UID`, NoiDung, `Type`, NgayThongBao) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $NoiDung = "Đề xuất " . $id . ": " . $status;
        $stmt->bind_param("isss", $result->fetch_assoc()['UID'], $NoiDung, $type, $date);
        $stmt->execute();
        $id_thong_bao = mysqli_insert_id($this->conn);
        $sql = "INSERT INTO loai_thong_bao (MaThongBao, MaDeXuat) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_thong_bao, $id);
        $stmt->execute();
        $content = $content != '' ? $content : $propose['GhiChu'];
        $sql = "UPDATE SAN_PHAM_DE_XUAT SET TrangThai = ?, GhiChu = ? WHERE MaDeXuat = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $content, $id);
        $stmt->execute();
        return ['success' => true, 'message' => 'Cập nhật thành công'];
    }

    public function statusComment($id, $trang_thai, $content) {
        if (!is_numeric($id)) return ['success' => false, 'message' => 'Không tìm thấy bình luận'];
        $sql = 'SELECT * FROM BINH_LUAN WHERE MaBinhLuan = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ['success'=> false, 'message'=> 'Không tìm thấy bình luận'];
        $sql = 'UPDATE BINH_LUAN SET TrangThai = ?, PhanHoi = ? WHERE MaBinhLuan = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssi', $trang_thai, $content, $id);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Cập nhật bình luận thành công'];
    }

    public function statusReview($id, $trang_thai, $content) {
        if (!is_numeric($id)) return ['success' => false, 'message' => 'Không tìm thấy đánh giá'];
        $sql = 'SELECT * FROM DANH_GIA WHERE MaDanhGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ['success'=> false, 'message'=> 'Không tìm thấy đánh giá'];
        $sql = 'UPDATE DANH_GIA SET TrangThai = ?, PhanHoi = ? WHERE MaDanhGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssi', $trang_thai, $content, $id);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Cập nhật đánh giá thành công'];
    }

    public function sale() {
        $sql = 'SELECT * FROM MA_GIAM_GIA ORDER BY ID_GiamGia DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }

    public function conditionSale() {
        $sql = 'SELECT DieuKien FROM MA_GIAM_GIA WHERE TrangThai != ?';
        $stmt = $this->conn->prepare($sql);
        $rac = 'Đã xóa';
        $stmt->bind_param('s', $rac);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row['DieuKien'];
        }
        return $result;
    }

    public function updateSale($ID_GiamGia, $Ma, $TienGiam, $DieuKien, $SoLuong, $TrangThai) {
        $rac = $this->getSaleById($ID_GiamGia);
        // Tiền xử lý dữ liệu
        $Ma = $Ma == '' ? $rac['Ma'] : $Ma;
        $TienGiam = $TienGiam < 0 ? $rac['TienGiam'] : $TienGiam;
        $DieuKien = $DieuKien == '' ? $rac['DieuKien'] : $DieuKien;
        $SoLuong = $SoLuong < 0 ? $rac['SoLuong'] : $SoLuong;
        $TrangThai = $TrangThai == '' ? $rac['TrangThai'] : $TrangThai; 
        // // Cập nhật database
        $sql = 'UPDATE MA_GIAM_GIA SET Ma = ?, TienGiam = ?, DieuKien = ?, SoLuong = ?, TrangThai = ? WHERE ID_GiamGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssiss', $Ma, $TienGiam, $DieuKien, $SoLuong, $TrangThai, $ID_GiamGia);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Cập nhật mã giảm giá thành thành công'];
    }

    public function setSale($Ma, $TienGiam, $DieuKien, $SoLuong) {
        if (!(is_numeric($TienGiam) && is_numeric($SoLuong) && $this->checkConditionSale($DieuKien))) return ['success'=> false,'message'=> 'Đầu vào không hợp lệ'];
        $sql = "INSERT INTO MA_GIAM_GIA (Ma, TienGiam, DieuKien, SoLuong) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisi", $Ma, $TienGiam, $DieuKien, $SoLuong);
        $stmt->execute();
        return ["success"=> true,"message"=> "Thêm khuyến mãi thành công"];
    }

    private function checkConditionSale($DieuKien) {
        switch ($DieuKien) {
            case 'Tất cả':
                return true;
            case 'Male':
                return true;
            case 'Female':
                return true;
            case 'Chẵn':   
                return true;
            case 'Lẻ':  
                return true;
            case 'COD':      
                return true;
            default:
                return false;
        }
    }

    private function getSaleById($id) {
        $sql = 'SELECT * FROM MA_GIAM_GIA WHERE ID_GiamGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt = $stmt->get_result();
        return $stmt->fetch_assoc();
    }

    private function proposeInfo() {
        $sql = "SELECT * FROM SAN_PHAM_DE_XUAT ORDER BY MaDeXuat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();   
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }

    private function productInfo() {
        $product_list = $this->product->get_admin();
        $product_info = [];
        foreach ($product_list as $product) {
            $product_info[] = $this->product->getProductInfo($product['ID_SP']);
        }
        return $this->support->sort($product_info);
    }
    public function updateProductInfo($ID_SP, $TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB) {
        $sql = "
        UPDATE SAN_PHAM SET
        TenSP = ?,
        MoTa = ?,
        Gia = ?,
        TyLeGiamGia = ?,
        SoLuongKho = ?,
        NXB = ?,
        KichThuoc = ?,
        SoTrang = ?,
        PhanLoai = ?,
        TuKhoa = ?,
        HinhThuc = ?,
        TacGia = ?,
        NgonNgu = ?,
        NamXB = ?
        WHERE ID_SP = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        // Check for SQL error in prepare statement
        return ["success" => false, "message" => "Database error: " . $this->conn->error];
    }

    $stmt->bind_param("ssidississsssii", $TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB, $ID_SP);
    $stmt->execute();

    $sqlDelete = "DELETE FROM HINH_ANH WHERE ID_SP = ?";
    $stmtDelete = $this->conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $ID_SP);
    $stmtDelete->execute();
    return ["success" => true, "message" => "Cập nhật thông tin sản phẩm thành công"];

    }

    public function updateProductImage ($Anh, $ID_SP) {
        $relativeAvatarPath = "public/image/product/$ID_SP/" . basename($Anh);
        // $sqlDelete = "DELETE FROM hinh_anh WHERE ID_SP = ?";
        // $stmtDelete = $this->conn->prepare($sqlDelete);
        // $stmtDelete->bind_param("i", $ID_SP);
        // $stmtDelete->execute();

        $sqlInsert = "INSERT INTO HINH_ANH (Anh, ID_SP) VALUES (?, ?)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bind_param("si", $relativeAvatarPath, $ID_SP);
        $stmtInsert->execute();
        return ["success" => true, "message" => "Cập nhật thành công"];
    }

    public function deleteProduct($ID_SP) {
        $sql = "UPDATE SAN_PHAM SET TrangThai = 'Đã xóa' WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
    
        if ($stmt === false) {
            error_log("Prepare failed: (" . $this->conn->error . ") " . $this->conn->error);
            return ["success" => false, "message" => "Prepare failed"];
        }
    
        $stmt->bind_param("i", $ID_SP);
        $stmt->execute();
    
        if ($stmt->affected_rows === 0) {
            $stmt->close();
            return ["success" => false, "message" => "Xóa sản phẩm không thành công"];
        }
    
        $stmt->close();
        $this->updateLike($ID_SP);
        $this->updateCart($ID_SP);
        return ["success" => true, "message" => "Xóa sản phẩm thành công"];
    }
    
    private function updateLike($ID_SP) {
        $sql = "DELETE FROM THICH WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ID_SP);
        $stmt->execute();
    }

    private function updateCart($ID_SP) {
        $sql = "DELETE FROM TRONG_GIO_HANG WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ID_SP);
        $stmt->execute();
    }
}
?>