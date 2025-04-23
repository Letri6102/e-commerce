<?php
require_once dirname(__DIR__, 1) . '/models/support.php';
require_once dirname(__DIR__, 1) . '/models/ProductService.php';
require_once dirname(__DIR__, 1) . '/models/OrderService.php';

class UserService {
    private $conn;
    private $support;
    private $product;
    private $order;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->support = new support();
        $this->product = new ProductService($conn);
        $this->order = new OrderService($conn);
    }

    public function info($id) {
        $user = $this->getUserInfo($id);
        if ($user['success'] === false) {
            return ['success' => false, 'message' => $user['message']];
        }
        
        $login = $this->getLoginInfo($id);
        if ($login['success'] === false) {
            return ['success' => false, 'message' => $login['message']];
        }

        if (!$user || !$login) {
            return ['success' => false, 'message' => 'Không tìm thấy người dùng'];
        }

        return [
            'name' => $login['name'],
            'role' => $login['role'],
            'phone' => $user['phone'],
            'sex' => $user['sex'],
            'address' => $user['address'],
            'avatar' => $login['avatar'],
        ];
    }

    public function diary($id) {
        $sql = "SELECT * FROM LICH_SU_DANG_NHAP WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $diary = [];
        while ($row = $result->fetch_assoc()) {
            $diary[] = [
                'ThoiGian' => $row['ThoiGian'],
                'NoiDung' => $row['NoiDung']
            ];
        }
        return $diary;
    }

    public function getBanner() {
        $sql = "SELECT MaBanner, Image, IdSP, MoTa FROM banner WHERE TrangThai != 'Đang ẩn'";
        $result = $this->conn->query($sql);

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không tìm thấy banner'];
        }
        
        $banner = [];
        while ($row = $result->fetch_assoc()) {
            $banner[] = $row;
        }
        return $banner;
    }

    public function getProduct() {
        $allProducts = $this->product->get();
        return $this->product->getList(array_slice($allProducts, -10));
    }

    public function getProductList() {
        $types = $this->product->getType();
        return $this->product->getProductList($types);
    }

    public function setAvatar($id, $avatarFileName) {
        $sql = "SELECT * FROM `LOGIN` WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Tài khoản không tồn tại'];
        }

        // Đường dẫn đầy đủ đến tệp ảnh
        $relativeAvatarPath = "/public/image/user/$id/" . basename($avatarFileName);
    
        // Lưu đường dẫn và tệp ảnh vào cơ sở dữ liệu
        $avatar_sql = "UPDATE `LOGIN` SET `Avatar` = ? WHERE `UID` = ?";
        $stmt_avatar = $this->conn->prepare($avatar_sql);
        $stmt_avatar->bind_param("si", $relativeAvatarPath, $id);
        $stmt_avatar->execute();

        $img = $relativeAvatarPath;
    
        return ['success' => true, 'message' => 'Thay đổi ảnh đại diện thành công', 'image' => $img];
    }    

    public function setInfo($uid, $sex, $phone, $addr, $name) {
        $user = $this->setUser($uid, $sex, $phone, $addr);
        $login = $this->setLogin($uid, $name);
        if ($user['success'] == false) return ['success'=> false,'message'=> $user['message']];
        if ($login['success'] == false) return ['success'=> false,'message'=> $login['message']];
        return ['success'=> true, 'message'=> 'Thay đổi thông tin thành công'];
    }

    public function setReview($uid, $ID_SP, $SoSao, $NoiDung) {
        $rac = $this->buyed($uid, $ID_SP);
        if ($rac['success'] == false) return $rac;
        if ($this->product->checkProduct($ID_SP) == false) return ['success'=> false,'message'=> 'Không tìm thấy sản phẩm'];
        $date = $this->support->getDateNow();
        $sql_check = "SELECT * FROM DANH_GIA WHERE UID = ? AND ID_SP = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $uid, $ID_SP);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $sql_update = "UPDATE DANH_GIA SET SoSao = ?, NoiDung = ?, NgayDanhGia = ? WHERE UID = ? AND ID_SP = ?";
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->bind_param("issii", $SoSao, $NoiDung, $date, $uid, $ID_SP);
            $stmt_update->execute();
        } 
        else {
            $sql_insert = "INSERT INTO DANH_GIA (UID, ID_SP, SoSao, NoiDung, NgayDanhGia) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $this->conn->prepare($sql_insert);
            $stmt_insert->bind_param("iiiss", $uid, $ID_SP, $SoSao, $NoiDung, $date);
            $stmt_insert->execute();
        }
        return ['success'=> true, 'message'=> 'Đánh giá thành công'];
    }

    public function setComment($uid, $ID_SP, $NoiDung) {
        if ($this->product->checkProduct($ID_SP) == false) return ['success'=> false,'message'=> 'Không tìm thấy sản phẩm'];
        $date = $this->support->getDateNow();

        $sql_insert = "INSERT INTO BINH_LUAN (`UID`, ID_SP, NoiDung, NgayBinhLuan) VALUES (?, ?, ?, ?)";
        $stmt_insert = $this->conn->prepare($sql_insert);
        $stmt_insert->bind_param("iiss", $uid, $ID_SP, $NoiDung, $date);
        $stmt_insert->execute();
        $newCommentId = $this->conn->insert_id;
        return ['success'=> true, 'message'=> 'Bình luận thành công', "idcmt" => $newCommentId];
    }

    public function like($uid, $ID_SP) {
        if ($this->product->checkProduct($ID_SP) == false) return ['success'=> false, 'message'=> 'Không tìm thấy sản phẩm'];
        if ($this->product->thich($ID_SP) == false) {
            $sql = "INSERT INTO THICH (`UID`, ID_SP) VALUES (?, ?)";
            $stmt_like = $this->conn->prepare($sql);
            $stmt_like->bind_param("ii", $uid, $ID_SP);
            $stmt_like->execute();
        }
        return ["success"=> true, "message"=> "Đã thích sản phẩm"];
    }

    public function unlike($uid, $ID_SP) {
        if ($this->product->thich($ID_SP) == false) return ['success'=> false, 'message'=> 'Chưa thích sản phẩm này!'];
        $sql = "DELETE FROM THICH WHERE `UID` = ? AND `ID_SP` = ?";
        $stmt_like = $this->conn->prepare($sql);
        $stmt_like->bind_param("ii", $uid, $ID_SP);
        $stmt_like->execute();
        return ["success"=> true, "message"=> "Bỏ thích thành công"];
    }

    public function getNotice($uid) {
        $sql = "SELECT * FROM THONG_BAO WHERE `UID` = ? ORDER BY MaThongBao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $notice = $stmt->get_result();
        $result = [];
        while ($row = $notice->fetch_assoc()) {
            if ($row['Type'] == 'Đơn hàng') {
                $sql = "SELECT ID_DonHang FROM LOAI_THONG_BAO WHERE MaThongBao = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $row['MaThongBao']);
                $stmt->execute();
                $stmt = $stmt->get_result();
                $row['ID_DonHang'] = $stmt->fetch_assoc()['ID_DonHang'];
                $result[] = [
                    'MaThongBao' => $row['MaThongBao'],
                    'noi_dung' => $row['NoiDung'],
                    'TrangThai' => $row['TrangThai'],
                    'type' => $row['Type'],
                    'ID_Redirect' => $row['ID_DonHang'],
                    'NgayThongBao'  => $row['NgayThongBao']
                ];
            }
            else if ($row['Type'] == 'Yêu cầu') {
                $sql = "SELECT MaDeXuat FROM LOAI_THONG_BAO WHERE MaThongBao = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $row['MaThongBao']);
                $stmt->execute();
                $stmt = $stmt->get_result();
                $row['MaDeXuat'] = $stmt->fetch_assoc()['MaDeXuat'];
                $result[] = [
                    'MaThongBao' => $row['MaThongBao'],
                    'noi_dung' => $row['NoiDung'],
                    'TrangThai' => $row['TrangThai'],
                    'type' => $row['Type'],
                    'ID_Redirect' => $row['MaDeXuat'],
                    'NgayThongBao'  => $row['NgayThongBao']
                ];
            }
            else return ['sucess' => false, 'message' => 'Đầu vào không hợp lệ'];
        }
        return ['sucess' => true, 'notice_list' => $result];
    }

    public function buyNow($uid, $PhuongThucThanhToan, $MaGiamGia, $SDT, $DiaChi, $TenNguoiNhan, $ID_SP, $SoLuong) {
        if ($MaGiamGia != '' && $this->order->orderSale($MaGiamGia) == false) {
            return ['success' => false, 'message' => 'Mã giảm giá không tồn tại'];
        }
        $bill = $this->order->getCostProduct($ID_SP) * $SoLuong;
        if ($bill == 0) return ['success'=> false, 'message' => 'Dữ liệu không hợp lệ'];
        $NgayDat = $this->support->getDateNow();
        if ($this->order->checkProduct($ID_SP, $SoLuong)['success'] == false)
            return ['success' => false, 'message' => 'Không đủ hàng trong kho'];
        if ($MaGiamGia == '') {
            $sql = "INSERT INTO DON_HANG (`UID`, NgayDat, TongTien, SDT, DiaChi, PhuongThucThanhToan, TenNguoiNhan, HoaDon) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isissssi", $uid, $NgayDat, $bill, $SDT, $DiaChi, $PhuongThucThanhToan, $TenNguoiNhan, $bill);
            $stmt->execute();
        }
        else {
            $sale = $this->order->sale($bill, $MaGiamGia, 0, $PhuongThucThanhToan)['tong_tien_phai_tra'];
            $sql = "INSERT INTO DON_HANG (`UID`, NgayDat, TongTien, MaGiamGia, SDT, DiaChi, PhuongThucThanhToan, TenNguoiNhan, HoaDon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isisssssi", $uid, $NgayDat, $bill, $MaGiamGia, $SDT, $DiaChi, $PhuongThucThanhToan, $TenNguoiNhan, $sale);
            $stmt->execute();
        }
        $id_don_hang = mysqli_insert_id($this->conn);
        $this->addProductToOrder($id_don_hang, $ID_SP, $SoLuong);
        $type = 'Đơn hàng';
        $date = $this->support->startTime();
        $sql = "INSERT INTO THONG_BAO (`UID`, NoiDung, `Type`, NgayThongBao) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $NoiDung = "Đơn hàng " . $id_don_hang . " được đặt thành công"; 
        $stmt->bind_param("isss", $uid, $NoiDung, $type, $date);
        $stmt->execute();
        $id_thong_bao = mysqli_insert_id($this->conn);
        $sql = "INSERT INTO LOAI_THONG_BAO (MaThongBao, ID_DonHang) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_thong_bao, $id_don_hang);
        $stmt->execute();
        return ['success' => true, 'message' => 'Đặt hàng thành công', 'ma_don_hang' => $id_don_hang];
    }

    public function addProductToOrder($ID_DonHang, $ID_SP, $SoLuong) {
        $sql = "INSERT INTO GOM (ID_DonHang, ID_SP, SoLuong) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $ID_DonHang, $ID_SP, $SoLuong);
        $stmt->execute();
        $this->order->orderProduct($ID_SP, $SoLuong);
    }

    public function updateStatusNotice($uid, $MaThongBao) {
        $sql = 'UPDATE THONG_BAO SET TrangThai = ? WHERE `UID` = ? AND MaThongBao = ?';
        $stmt = $this->conn->prepare($sql);
        $rac1 = 'Read';
        $stmt->bind_param('sis', $rac1, $uid, $MaThongBao);
        $stmt->execute();
        return ['success' => true, 'message' => 'Xem thông báo thành công'];
    }

    private function buyed ($uid, $ID_SP) {
        $sql = "SELECT TrangThai 
                FROM DON_HANG D 
                JOIN GOM G ON G.ID_DonHang = D.ID_DonHang 
                WHERE G.ID_SP = ? AND D.UID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $ID_SP, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ["success"=> false,"message"=> "Người dùng chưa mua sản phẩm"];
        $result = $result->fetch_assoc();
        if ($result['TrangThai'] != 'Đã giao hàng') return ['success'=> false,'message'=> 'Người dùng chưa mua sản phẩm'];
        return ['success'=> true, 'message' => $result['TrangThai']];
    }

    private function setUser($uid, $sex, $phone, $addr) {
        if ($sex !== '' && $sex !== 'Male' && $sex !== 'Female' && $phone !== '' && !preg_match('/^[0-9]+$/', $phone)) {
            return ['success'=> false, 'message'=> 'Dữ liệu không hợp lệ'];
        }
        $user = $this->getUserInfo($uid);
        if ($user['success'] === false) {
            return ["success" => false, "message" => $user['message']];
        }
        if ($sex == '') $sex = $user['sex'];
        if ($phone == '') $phone = $user['phone'];
        if ($addr == '') $addr = $user['address'];
        $sql = "UPDATE KHACH_HANG SET GioiTinh = ?, SDT = ?, DiaChi = ? WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $sex, $phone, $addr, $uid);
        $stmt->execute();
        return ["success" => true];
    }

    private function setLogin($uid, $name) {
        $login = $this->getLoginInfo($uid);
        if ($login['success'] === false) {
            return ["success" => false, "message" => $login['message']];
        }
        if ($name == '') $name = $login['name'];
        $sql = "UPDATE `LOGIN` SET Ten = ? WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $uid);
        $stmt->execute();
        return ["success" => true];
    }

    public function getUserInfo($uid) {
        $sql = "SELECT * FROM KHACH_HANG WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ["success" => false,"message" => "Không tìm thấy thông tin người dùng"];
        }
        $user = $result->fetch_assoc();
        return ["success" => true, 'phone' => $user['SDT'], 'sex' => $user['GioiTinh'], 'address' => $user['DiaChi'], 'status' => $user['TrangThai']];
    }

    public function getLoginInfo($uid) {
        $sql = "SELECT * FROM `LOGIN` WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ["success" => false,"message" => "Không tìm thấy thông tin đăng nhập"];
        }
        $user = $result->fetch_assoc();
        return ["success" => true, 'name' => $user['Ten'], 'role' => $user['Role'], 'avatar' => $user['Avatar'], 'password' => $user['Password'], 'email' => $user['Email']];
    }
}
?>