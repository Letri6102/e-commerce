<?php
require_once dirname(__DIR__, 1) . '/models/support.php';
require_once dirname(__DIR__, 1) . '/models/ProductService.php';
require_once dirname(__DIR__, 1) . '/models/CartService.php';

class OrderService {
    private $conn;
    private $support;
    private $product;
    private $cart;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->support = new support();
        $this->product = new ProductService($conn);
        $this->cart = new CartService($conn);
    }

    public function sale($tienHang, $MaGiamGia, $phiVanChuyen, $PhuongThucThanhToan) {
        $sql1 = "SELECT * FROM `MA_GIAM_GIA` WHERE `Ma` = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->bind_param("s", $MaGiamGia);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows == 0) {
            return ['success' => false, 'message' => 'Mã giảm giá không tồn tại'];
        }
        $row = $result1->fetch_assoc();
        if ($row['TrangThai'] == 'Hết hạn') {
            return ['success'=> false, 'message'=> 'Mã giảm giá đã hết hạn'];
        }
        if ($row['TrangThai'] == 'Đã xóa') {
            return ['success'=> false, 'message'=> 'Mã giảm giá không tồn tại'];
        }
        
        $id = $_SESSION["uid"];
        $sql2 = "SELECT GioiTinh FROM `KHACH_HANG` WHERE `UID` = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $sex = $result2->fetch_assoc();

        $money = $this->support->handle_charge($MaGiamGia, $row['TienGiam'], $row['DieuKien'], $tienHang, $phiVanChuyen, $PhuongThucThanhToan, $sex['GioiTinh']);
        return $money;
    }

    public function getPaid($uid) {
        $sql1 = "SELECT ID_DonHang, NgayDat, TrangThai FROM DON_HANG WHERE `UID` = ? ORDER BY ID_DonHang DESC";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->bind_param("i", $uid);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows === 0) {
            return ['success' => false, 'message' => 'Không có đơn hàng đã thanh toán'];
        }

        $order = [];
        while ($row = $result1->fetch_assoc()) {
            $order[] = $row;
        }

        $result = [];
        foreach ($order as $row) {
            $idOrder = $row['ID_DonHang'];
            $sql2 = "SELECT ID_SP, SUM(SoLuong) AS COUNT FROM GOM WHERE ID_DonHang = ? GROUP BY ID_SP";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->bind_param("i", $idOrder);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($result2->num_rows === 0) {
                return ['success' => false, 'message' => 'Đơn hàng khộng có sản phẩm'];
            }
            $product = $result2->fetch_assoc();

            if ($product) {
                $id = $product['ID_SP'];
                $image = $this->product->getImage($id);
                $sql3 = "SELECT TenSP, Gia FROM SAN_PHAM WHERE ID_SP = ?";
                $stmt3 = $this->conn->prepare($sql3);
                $stmt3->bind_param("i", $id);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                if ($result3->num_rows === 0) {
                    return ['success' => false, 'message' => 'Không tìm thấy sản phẩm'];
                }    
                $productDetails = $result3->fetch_assoc();

                $result[] = [
                    'id' => $idOrder,
                    'hinh' => $image,
                    'ten_sp' => $productDetails['TenSP'],
                    'gia' => $productDetails['Gia'],
                    'trang_thai' => $row['TrangThai'],
                    'ngay_dat' => $row['NgayDat'],
                    'so_luong_san_pham' => $product['COUNT']
                ];
            }
        }
        return ['success' => true, 'message' => $result];
    }

    private function review($ID_SP, $uid) {
        $sql = "SELECT * FROM DANH_GIA WHERE ID_SP = ? AND `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $ID_SP, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) return true;
        return false;
    }

    public function getInfo($uid, $ID_DonHang) {
        $sql = "SELECT NgayDat, ThanhToan, TrangThai, SDT, DiaChi, TenNguoiNhan FROM DON_HANG WHERE `UID` = ? AND ID_DonHang = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $uid, $ID_DonHang);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false,'message' => 'Người dùng không có đơn hàng'];
        }

        $order = $result->fetch_assoc();
        
        $money = $this->getPayment($ID_DonHang);
        if ($money['status'] === false) return ['success' => false, 'message' => $money['message']];

        $user = $this->getUser($uid);
        if ($user['success'] === false) return ['success' => false, 'message' => $user['message']];

        if ($order['TrangThai'] == 'Đã giao hàng') $product = $this->getProduct($ID_DonHang, $uid, 1);
        else $product = $this->getProduct($ID_DonHang, $uid, 0);
        if ($product['success'] === false) return ['success' => false, 'message' => $product['message']];
        $product = $product['san_pham'];
        $so_luong_san_pham = count($product);

        return [
            'success' => true,
            'info' => [
                'id' => $ID_DonHang,
                'ngay_dat' => $order['NgayDat'],
                'trang_thai' => $order['TrangThai'],
                'thanh_toan' => $order['ThanhToan'],
                'danh_sach_san_pham' => $product,
                'so_luong_san_pham' => $so_luong_san_pham,
                'thong_tin_thanh_toan' => $money['thanh_toan'],
                'ten_khach_hang' => $user['ten'],
                'ten_nguoi_nhan' => $order['TenNguoiNhan'],
                'so_dien_thoai' => $order['SDT'],
                'dia_chi_giao_hang' => $order['DiaChi'],
            ]
        ];
    }

    public function order($uid, $PhuongThucThanhToan, $MaGiamGia, $SDT, $DiaChi, $TenNguoiNhan, $product_list) {
        if ($MaGiamGia != '' && $this->orderSale($MaGiamGia) == false) {
            return ['success' => false, 'message' => 'Mã giảm giá không tồn tại'];
        }
        $bill = $this->orderCost($uid, $product_list);
        if ($bill == 0) return ['success'=> false, 'message' => 'Danh sách sản phẩm không hợp lệ'];
        $NgayDat = $this->support->getDateNow();
        foreach ($product_list as $ID_SP) {
            if ($this->checkProduct($ID_SP, $this->countProduct($uid, $ID_SP))['success'] == false)
            return ['success' => false, 'message' => 'Không đủ hàng trong kho'];
        }
        if ($MaGiamGia == '') {
            $sql = "INSERT INTO DON_HANG (`UID`, NgayDat, TongTien, SDT, DiaChi, PhuongThucThanhToan, TenNguoiNhan, HoaDon) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isissssi", $uid, $NgayDat, $bill, $SDT, $DiaChi, $PhuongThucThanhToan, $TenNguoiNhan, $bill);
            $stmt->execute();
        }
        else {
            $sale = $this->sale($bill, $MaGiamGia, 0, $PhuongThucThanhToan)['tong_tien_phai_tra'];
            $sql = "INSERT INTO DON_HANG (`UID`, NgayDat, TongTien, MaGiamGia, SDT, DiaChi, PhuongThucThanhToan, TenNguoiNhan, HoaDon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isisssssi", $uid, $NgayDat, $bill, $MaGiamGia, $SDT, $DiaChi, $PhuongThucThanhToan, $TenNguoiNhan, $sale);
            $stmt->execute();
        }
        $id_don_hang = mysqli_insert_id($this->conn);
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

    public function update($trang_thai, $thanh_toan, $id) {
        if ($trang_thai == '' && $thanh_toan == '') return ['success' => false, 'message' => 'Chưa điền đầy đủ thông tin'];
        $order = $this->getOrderById($id);
        $type = 'Đơn hàng';
        $date = $this->support->startTime();
        if ($trang_thai != '') {
            $sql = "INSERT INTO THONG_BAO (`UID`, NoiDung, `Type`, NgayThongBao) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $NoiDung = "Đơn hàng " . $id . ": " . $trang_thai;
            $stmt->bind_param("isss", $order['UID'], $NoiDung, $type, $date);
            $stmt->execute();
            $id_thong_bao = mysqli_insert_id($this->conn);
            $sql = "INSERT INTO LOAI_THONG_BAO (MaThongBao, ID_DonHang) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_thong_bao, $id);
            $stmt->execute();
        }
        if ($thanh_toan != '') {
            $sql = "INSERT INTO THONG_BAO (`UID`, NoiDung, `Type`, NgayThongBao) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $NoiDung = "Đơn hàng " . $id . ": " . $thanh_toan . " thành công";
            $stmt->bind_param("isss", $order['UID'], $NoiDung, $type, $date);
            $stmt->execute();
            $id_thong_bao = mysqli_insert_id($this->conn);
            $sql = "INSERT INTO LOAI_THONG_BAO (MaThongBao, ID_DonHang) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_thong_bao, $id);
            $stmt->execute();
        }
        $thanh_toan = $thanh_toan != '' ? $thanh_toan : $order['ThanhToan'];
        $trang_thai = $trang_thai != '' ? $trang_thai : $order['TrangThai'];
        if ($trang_thai == 'Đã giao hàng') $thanh_toan = 'Đã thanh toán';
        $sql = "UPDATE DON_HANG SET ThanhToan = ?, TrangThai = ? WHERE ID_DonHang =?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $thanh_toan, $trang_thai, $id);
        $stmt->execute();
        return ['success' => true, 'message' => 'Cập nhật đơn hàng thành công', 'ma_don_hang' => $id];
    }

    public function list() {
        $sql = "SELECT `UID`, ID_DonHang FROM DON_HANG ORDER BY ID_DonHang DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ["success"=> false,"message"=> "Không có đơn hàng nào"];
        }
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = [
                'UID' => $row['UID'],
                'ID_DonHang'=> $row['ID_DonHang'],
            ];
        }
        $list = [];
        foreach ($orders as $order) {
            $info = $this->getInfo($order['UID'], $order['ID_DonHang']);
            if ($info['success'] == true) 
                $list[] = $info;
        }
        return ['success'=> true,'list'=> $list];
    }

    public function profile($uid) {
        $sql = 'SELECT COUNT(*) AS count FROM DON_HANG WHERE `UID` = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $num = $result->fetch_assoc()['count'];
        $sql = 'SELECT TrangThai, HoaDon
                FROM DON_HANG 
                WHERE `UID` = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row;
        }
        return ['success' => true, 'So_tien_da_mua' => $this->purchasedAmount($result), 'So_don_hang' => $num];
    }

    public function delete($uid, $ID_DonHang) {
        $sql = 'SELECT * FROM `DON_HANG` WHERE `UID` = ? AND ID_DonHang = ? AND (TrangThai = "Chờ xác nhận" OR TrangThai = "Đã xác nhận")';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $uid, $ID_DonHang);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ['success' => false, 'message' => 'Không tìm thấy đơn hàng'];
        $sql = 'UPDATE don_hang SET TrangThai = "Đã hủy" WHERE ID_DonHang = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $ID_DonHang);
        $stmt->execute();
        $this->cancelOrder($this->getProductOrder($ID_DonHang));
        return ['success' => true, 'message' => 'Hủy đơn hàng thành công'];
    }

    private function getProductOrder($ID_DonHang) {
        $sql = 'SELECT ID_SP, SoLuong FROM GOM WHERE ID_DonHang =?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $ID_DonHang);
        $stmt->execute();
        $result = $stmt->get_result();
        $product_list = [];
        while ($row = $result->fetch_assoc()) {
            $product_list[] = $row;
        }
        return $product_list;
    }

    private function cancelOrder($product_list) {
        foreach ($product_list as $row) {
            $sql = 'SELECT SoLuongKho FROM san_pham WHERE ID_SP = ?';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $row['ID_SP']);
            $stmt->execute();
            $result = $stmt->get_result();
            $result = $result->fetch_assoc();
            $new_quantity = $result['SoLuongKho'] + $row['SoLuong'];
            $sql = 'UPDATE san_pham SET SoLuongKho = ? WHERE ID_SP = ?';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $new_quantity, $row['ID_SP']);
            $stmt->execute();
        }
    }

    private function purchasedAmount($data) {
        $money = 0;
        foreach ($data as $order) {
            if ($order['TrangThai'] == 'Đã giao hàng')
            $money += $order['HoaDon'];
        }
        return $money;
    }

    private function orderCost($uid, $product_list) {
        $cost = 0;
        foreach ($product_list as $ID_SP) {
            $count = $this->countProduct($uid, $ID_SP);
            $bill = $count * $this->getCostProduct($ID_SP);
            $cost += $bill;
        }
        return $cost;
    }

    public function checkCart($uid) {
        $sql = 'SELECT * FROM TRONG_GIO_HANG WHERE `UID` = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return false;
        return true;
    }

    private function checkSale($MaGiamGia) {
        $sql = "SELECT SoLuong FROM `MA_GIAM_GIA` WHERE `Ma` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $MaGiamGia);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ['success' => false];
        return ['success' => true, 'so_luong' => $result->fetch_assoc()['SoLuong']];
    }

    public function orderSale($MaGiamGia) {
        $sale = $this->checkSale($MaGiamGia);
        if ($sale['success'] == false) return false;
        $rac = $sale['so_luong'] - 1;
        if ($rac >= 0) {
            $sql = 'UPDATE MA_GIAM_GIA SET SoLuong = ? WHERE Ma = ?';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $rac, $MaGiamGia);
            $stmt->execute();
        }
        else return false;
        return true;
    }

    public function checkProduct($ID_SP, $quantity) {
        $sql = "SELECT SoLuongKho FROM SAN_PHAM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ID_SP);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc()['SoLuongKho'];
        if ($result < $quantity) return ['success' => false];
        return ['success'=> true, 'so_luong'=> $result];
    }

    public function orderProduct($ID_SP, $quantity) {
        $rac = $this->checkProduct($ID_SP, $quantity);
        if ($rac['success'] == false) return false;
        $num = $rac['so_luong'];
        if ($num >= $quantity) {
            $num -= $quantity;
            $sql = 'UPDATE SAN_PHAM SET SoLuongKho = ? WHERE ID_SP = ?';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $num, $ID_SP);
            $stmt->execute();
        }
        return true;
    }

    public function addProductToOrder($ID_DonHang, $uid, $product_list) {
        foreach ($product_list as $ID_SP) {
            $rac = $this->countProduct($uid, $ID_SP);
            if ($rac != 0) {
                $sql = "INSERT INTO GOM (ID_DonHang, ID_SP, SoLuong) VALUES (?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("iii", $ID_DonHang, $ID_SP, $rac);
                $stmt->execute();
                $this->removeProductFromCart($uid, $ID_SP);
                $this->orderProduct($ID_SP, $rac);
            }
        }
    }

    private function removeProductFromCart($uid, $product_id) {
        $this->cart->remove($uid, $product_id);
    }

    private function countProduct($uid, $ID_SP) {
        $sql = "SELECT SoLuong FROM TRONG_GIO_HANG WHERE `UID` = ? AND ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $uid, $ID_SP);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return 0;
        return $result->fetch_assoc()['SoLuong'];
    }

    public function getCostProduct($ID_SP) {
        $sql = "SELECT Gia, TyLeGiamGia FROM SAN_PHAM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ID_SP);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        return round($result['Gia'] - $result['Gia'] * $result['TyLeGiamGia'], 2);
    }

    private function getProduct($orderId, $uid, $status) {
        $sql = "SELECT ID_SP, SoLuong FROM GOM WHERE ID_DonHang = ? ORDER BY ID_SP DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            return ["success" => false, "message" => "Không tìm thấy đơn hàng"];
        }
    
        $order = [];
        while ($row = $result->fetch_assoc()) {
            $order[] = $row;
        }
    
        $resultList = [];
        if ($status) {
            foreach ($order as $products) {
                $product = $this->getProductById($products['ID_SP']);
                if ($product['success'] === false) {
                    return ['success' => false, 'message' => $product['message']];
                }
        
                $resultList[] = [
                    'id' => $product['ID_SP'],
                    'anh' => $this->product->getImage($product['ID_SP']),
                    'ten' => $product['TenSP'],
                    'so_luong' => $products['SoLuong'],
                    'gia_san_pham' => $product['Gia'],
                    'gia_sau_giam_gia' => round($product['Gia'] * (1 - $product['TyLeGiamGia']), 2),
                    'isReviewed' => $this->review($products['ID_SP'], $uid)
                ];
            }
        }
        else {
            foreach ($order as $products) {
                $product = $this->getProductById($products['ID_SP']);
                if ($product['success'] === false) {
                    return ['success' => false, 'message' => $product['message']];
                }
        
                $resultList[] = [
                    'id' => $product['ID_SP'],
                    'anh' => $this->product->getImage($product['ID_SP']),
                    'ten' => $product['TenSP'],
                    'so_luong' => $products['SoLuong'],
                    'gia_san_pham' => $product['Gia'],
                    'gia_sau_giam_gia' => round($product['Gia'] * (1 - $product['TyLeGiamGia']), 2),
                    'isReviewed' => false
                ];
            }
        }
    
        return [
            "success" => true,
            "san_pham" => $resultList
        ];
    }

    private function getPayment($orderId) {
        $sql = "SELECT TongTien, ThanhToan, PhuongThucThanhToan, MaGiamGia, HoaDon FROM DON_HANG WHERE ID_DonHang = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            return ['status' => false, 'message' => 'Không tìm thấy thông tin thanh toán cho đơn hàng'];
        }
    
        $order = $result->fetch_assoc();
        if ($order['MaGiamGia'] == null) {
            return [
                'status' => true,
                'thanh_toan' => [
                    'tong_tien' => $order['TongTien'],
                    'so_tien_da_giam' => 0,
                    'tong_tien_phai_tra' => $order['TongTien'],
                    'trang_thai' => $order['ThanhToan'],
                    'phuong_thuc' => $order['PhuongThucThanhToan'],
                    'ma_giam_gia' => $order['MaGiamGia'],
                ]
            ];
        }
        else {
            return [
                'status' => true,
                'thanh_toan' => [
                    'tong_tien' => $order['TongTien'],
                    'so_tien_da_giam' => $order['TongTien'] - $order['HoaDon'],
                    'tong_tien_phai_tra' => $order['HoaDon'],
                    'trang_thai' => $order['ThanhToan'],
                    'phuong_thuc' => $order['PhuongThucThanhToan'],
                    'ma_giam_gia' => $order['MaGiamGia'],
                ]
            ];
        }
    }    

    private function getUser($uid) {  
        $sql = "SELECT Ten FROM `LOGIN` WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không tìm thấy thông tin đăng nhập'];
        }
    
        $user = $result->fetch_assoc();
        return [
            'success' => true,
            'ten' => $user['Ten']
        ];
    }    

    private function getProductById($id) {
        $sql = "SELECT * FROM SAN_PHAM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không có sản phẩm'];
        }
    
        $product = $result->fetch_assoc();
        $product['success'] = true;
        return $product;
    }

    private function getOrderById($ID_DonHang) {
        $sql = "SELECT * FROM DON_HANG WHERE ID_DonHang =?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ID_DonHang);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không tìm thấy đơn hàng'];
        }
        return $result->fetch_assoc();

    }
}
?>