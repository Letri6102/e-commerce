<?php
require_once dirname(__DIR__, 1) . '/models/support.php';

class ProductService {
    private $conn;
    private $support;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->support = new support();
    }

    public function getInfo($id, $status) {
        $result = $this->getProductInfo($id);
        $review = $this->average_star($id, $status);
        $result['danh_sach_danh_gia'] = $review['danh_sach_danh_gia'];
        $result['danh_sach_binh_luan'] = $this->getComment($id, $status);
        $product = $this->get();
        $result['danh_sach_san_pham'] = $this->getList(array_slice($product, -10));
        return $result;
    }

    public function getProductInfo($id) {
        $product = $this->getProductById($id);
        if (!$product) {
            return ['success' => false, 'message' => 'Không tìm thấy sản phẩm'];
        }
        $review = $this->average_star($id, 1)['so_sao_trung_binh'];
        return [
            'id' => $id,
            'ten' => $product['TenSP'],
            'hinh' => $this->getImage($id),
            'so_sao_trung_binh' => $review,
            'so_luong_da_ban' => $this->countProduct($id),
            'thong_tin_chi_tiet' => [
                'the_loai' => $product['PhanLoai'],
                'tac_gia' => $product['TacGia'],
                'nha_xuat_ban' => $product['NXB'],
                'hinh_thuc' => $product['HinhThuc'],
                'nam_xuat_ban' => $product['NamXB'],
                'ngon_ngu' => $product['NgonNgu'],
                'kich_thuoc' => $product['KichThuoc'],
                'so_trang' => $product['SoTrang'],
                'tu_khoa' => $product['TuKhoa']
            ],
            'gia_san_pham' => $product['Gia'],
            'gia_sau_giam_gia' => round($product['Gia'] * (1 - $product['TyLeGiamGia']), 2),
            'ty_le_giam_gia' => $product['TyLeGiamGia'] * 100 . '%',
            'so_luong_ton_kho' => $product['SoLuongKho'],
            'mo_ta' => $product['MoTa'],
            'trang_thai' => $product['TrangThai']
        ];
    }

    public function getList($latest) {
        $result = [];
        foreach ($latest as $entry) {
            $id = $entry['ID_SP'];
            $trang_thai = $this->thich($id);
            $base_data = [
                'id' => $id,
                'ten' => $entry['TenSP'],
                'hinh' => $this->getImage($id),
                'gia_goc' => $entry['Gia'],
                'gia_sau_giam_gia' => round($entry['Gia'] * (1 - $entry['TyLeGiamGia']), 2),
                'so_sao_trung_binh' => $this->average_star($id, 1)['so_sao_trung_binh']
            ];
            switch ($trang_thai) {
                case -1:
                    $result[] = $base_data;
                    break;
                case 0:
                    $result[] = array_merge($base_data, ['thich' => false]);
                    break;
                case 1:
                    $result[] = array_merge($base_data, ['thich' => true]);
                    break;
                default:
                    break;
            }
        }
        return $this->support->sort($result);
    }

    public function getProductList($typeList) {
        $result = [];
        foreach ($typeList as $type) {
            $sql = "SELECT * FROM SAN_PHAM WHERE PhanLoai = ? AND TrangThai != 'Đã xóa'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $type);
            $stmt->execute();
            $result_type = $stmt->get_result();
            
            $product = [];
            while ($row = $result_type->fetch_assoc()) {
                $product[] = $row;
            }
            $result[] = [
                'the_loai' => $type,
                'san_pham' => $this->getList($product)
            ];
        }
        return $result;
    }

    public function get() {
        $sql = "SELECT * FROM SAN_PHAM WHERE TrangThai = 'Đang hiện'";
        $result = $this->conn->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function get_admin() {
        $sql = "SELECT * FROM SAN_PHAM WHERE TrangThai = 'Đang hiện' OR TrangThai = 'Đang ẩn'";
        $result = $this->conn->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function getType() {
        $sql = "SELECT DISTINCT PhanLoai FROM SAN_PHAM";
        $result = $this->conn->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row['PhanLoai'];
        }
        return $products;
    }

    public function getImage($id) {
        $sql = "SELECT Anh FROM HINH_ANH WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row['Anh'];
        }
        return $images;
    }

    public function checkProduct($id) {
        $sql = "SELECT * FROM SAN_PHAM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }

        return true;
    }

    public function proposeProduct($TenSP, $NoiDung, $uid, $GhiChu) {
        $sql = "SELECT * FROM SAN_PHAM_DE_XUAT WHERE TenSP = ? AND `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $TenSP, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Người dùng đã đề xuất'];
        }
        $time = $this->support->getDateNow();
        $sql = "INSERT INTO SAN_PHAM_DE_XUAT (TenSP, NoiDung, `UID`, GhiChu, NgayYeuCau) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiss", $TenSP, $NoiDung, $uid, $GhiChu, $time);
        $stmt->execute();
        $id_de_xuat =  mysqli_insert_id($this->conn);
        $type = 'Yêu cầu';
        $date = $this->support->startTime();
        $sql = "INSERT INTO THONG_BAO (`UID`, NoiDung, `Type`, NgayThongBao) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $NoiDung = "Đề xuất " . $id_de_xuat . " thành công"; 
        $stmt->bind_param("isss", $uid, $NoiDung, $type, $date);
        $stmt->execute();
        $id_thong_bao = mysqli_insert_id($this->conn);
        $sql = "INSERT INTO LOAI_THONG_BAO (MaThongBao, MaDeXuat) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_thong_bao, $id_de_xuat);
        $stmt->execute();
        return ['success' => true, 'message' => 'Đề xuất thành công'];
    }

    public function like($uid) {
        $sql = "SELECT ID_SP FROM THICH WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ["success"=> false,"message"=> "Chưa thích sản phẩm nào"];
        }
        $productId = [];
        while ($row = $result->fetch_assoc()) {
            $productId[] = $row['ID_SP'];
        }
        $product = [];
        foreach ($productId as $id) {
            $product[] = $this->getProductById($id);
        }
        return ['success' => true, 'product_list' => $this->getList($product)];
    }

    public function keywork($keyword) {
        $stmt = "SELECT ID_SP 
                 FROM SAN_PHAM 
                 WHERE TenSp REGEXP ?
                 OR MoTa REGEXP ?
                 OR Gia REGEXP ?
                 OR TyLeGiamGia REGEXP ?
                 OR NXB REGEXP ?
                 OR SoTrang REGEXP ?
                 OR PhanLoai REGEXP ?
                 OR TuKhoa REGEXP ?
                 OR HinhThuc REGEXP ?
                 OR TacGia REGEXP ?
                 OR NgonNgu REGEXP ?
                 OR NamXB REGEXP ?";
        $stmt = $this->conn->prepare($stmt);
        $stmt->bind_param( 'ssssssssssss', $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $results = [];
        while ($row = $stmt->fetch_assoc()) {
            $results[] = $this->getProductById($row['ID_SP']);
        }
        $results = $this->getList($results);
        if ($results != []) return ['success' => true, 'message' => $results];
        else return ['success' => false, 'message' => 'Không tìm thấy sản phẩm!'];
    }

    public function getPropose($uid) {
        $sql = "SELECT MaDeXuat, TenSP, NoiDung, TrangThai, NgayYeuCau FROM SAN_PHAM_DE_XUAT WHERE `UID` = ? ORDER BY MaDeXuat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt = $stmt->get_result();
        if ($stmt->num_rows === 0) {
            return ['success' => false, 'message' => 'Chưa đề xuất sản phẩm nào!'];
        }
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row;
        }
        return ['success' => true, 'danh_sach_de_xuat' => $result];
    }

    public function proposeInfo($uid, $MaDeXuat) {
        $sql = "SELECT * FROM SAN_PHAM_DE_XUAT WHERE `UID` = ? AND MaDeXuat = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $uid, $MaDeXuat);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Chưa đề xuất!'];
        }
        return ['success' => true, 'chi_tiet_de_xuat' => $result->fetch_assoc()];
    }

    public function getSale() {
        $sql = "SELECT ID_GiamGia, Ma, TienGiam, DieuKien, SoLuong FROM MA_GIAM_GIA WHERE TrangThai != 'Hết hạn' ORDER BY ID_GiamGia DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $sale = [];
        while ($row = $result->fetch_assoc()) {
            $sale[] = [
                'ID_GiamGia' => $row['ID_GiamGia'],
                'ma' => $row['Ma'],
                'tien_giam' => $row['TienGiam'],
                'dieu_kien' => $row['DieuKien'],
                'so_luong' => $row['SoLuong'],
            ];
        }
        return ['success' => true, 'danh_sach_giam_gia' => $sale];
    }

    private function getProductById($id) {
        $sql = "SELECT * FROM SAN_PHAM WHERE ID_SP = ? AND TrangThai != 'Đã xóa'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return null;
        }
        return $result->fetch_assoc();
    }

    private function getComment($id, $status) {
        $sql = "
            SELECT BINH_LUAN.MaBinhLuan, BINH_LUAN.NgayBinhLuan, BINH_LUAN.NoiDung, LOGIN.Ten, LOGIN.Avatar, BINH_LUAN.TrangThai, BINH_LUAN.PhanHoi
            FROM BINH_LUAN
            JOIN LOGIN ON BINH_LUAN.UID = LOGIN.UID
            WHERE BINH_LUAN.ID_SP = ?
            ORDER BY BINH_LUAN.MaBinhLuan DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [];
        }
        
        $comments = [];
        if ($status == 1) {
            while ($row = $result->fetch_assoc()) {
                if ($row['TrangThai'] != 'Đã xóa')
                $comments[] = [
                    'id' => $row['MaBinhLuan'],
                    'ngay_binh_luan' => $row['NgayBinhLuan'],
                    'noi_dung' => $row['NoiDung'],
                    'avatar' => $row['Avatar'],
                    'ten' => $row['Ten'],
                    'trang_thai' => $row['TrangThai'],
                    'phan_hoi' => $row['PhanHoi']
                ];
            }
        } 
        else {
            while ($row = $result->fetch_assoc()) {
                if ($row['TrangThai'] == 'Đang hiện')
                $comments[] = [
                    'id' => $row['MaBinhLuan'],
                    'ngay_binh_luan' => $row['NgayBinhLuan'],
                    'noi_dung' => $row['NoiDung'],
                    'avatar' => $row['Avatar'],
                    'ten' => $row['Ten'],
                    'phan_hoi' => $row['PhanHoi']
                ];
            }
        }
        return $comments;
    }

    private function getReview($id, $status) {
        $sql = "
            SELECT DANH_GIA.MaDanhGia, DANH_GIA.NgayDanhGia, DANH_GIA.SoSao, DANH_GIA.NoiDung, LOGIN.Ten, LOGIN.Avatar, DANH_GIA.TrangThai, DANH_GIA.PhanHoi
            FROM DANH_GIA 
            JOIN LOGIN ON DANH_GIA.UID = LOGIN.UID
            WHERE ID_SP = ?
            ORDER BY DANH_GIA.MaDanhGia DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không có đánh giá'];
        }
        
        $reviews = [];
        if ($status == 1) {
            while ($row = $result->fetch_assoc()) {
                if ($row['TrangThai'] != 'Đã xóa')
                $reviews[] = [
                    'id' => $row['MaDanhGia'],
                    'ngay_danh_gia' => $row['NgayDanhGia'],
                    'so_sao' => $row['SoSao'],
                    'noi_dung' => $row['NoiDung'],
                    'avatar' => $row['Avatar'],
                    'ten' => $row['Ten'],
                    'trang_thai' => $row['TrangThai'],
                    'phan_hoi' => $row['PhanHoi']
                ];
            }
        }
        else {
            while ($row = $result->fetch_assoc()) {
                if ($row['TrangThai'] == 'Đang hiện')
                $reviews[] = [
                    'id' => $row['MaDanhGia'],
                    'ngay_danh_gia' => $row['NgayDanhGia'],
                    'so_sao' => $row['SoSao'],
                    'noi_dung' => $row['NoiDung'],
                    'avatar' => $row['Avatar'],
                    'ten' => $row['Ten'],
                    'phan_hoi' => $row['PhanHoi']
                ];
            }
        }
        return $reviews;
    }

    private function average_star($id, $status) {
        $review = $this->getReview($id, $status);
        if (isset($review['success']) && !$review['success']) {
            return [
                'so_sao_trung_binh' => 0,
                'danh_sach_danh_gia' => []
            ];
        }
    
        $sum_star = 0;
        $count_star = 0;
    
        foreach ($review as $entry) {
            $sum_star += $entry['so_sao'];
            $count_star++;
        }
    
        return [
            'so_sao_trung_binh' => $count_star > 0 ? round($sum_star / $count_star, 1) : 0,
            'danh_sach_danh_gia' => $review
        ];
    }
    
    private function countProduct($id) {
        $sql = "SELECT SUM(SoLuong) as total FROM GOM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data['total'] ?? 0;
    }

    public function thich($id) {
        if (isset($_SESSION["uid"])) {
            $uid = $_SESSION["uid"];
            $sql = "SELECT 1 FROM THICH WHERE ID_SP = ? AND `UID` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id, $uid);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->num_rows ? 1 : 0;
        }
        return -1;
    }

    public function productType($type, $login, $uid) {
        $sql1 = "SELECT 
                    SAN_PHAM.ID_SP, 
                    SAN_PHAM.TenSP, 
                    SAN_PHAM.Gia,
                    ROUND((SAN_PHAM.Gia*(1-SAN_PHAM.TyLeGiamGia))) AS GiaSauGiam,
                    ROUND(AVG(DANH_GIA.SoSao), 0) AS SoSao,
                    false AS YeuThich,
                    (SELECT Anh FROM HINH_ANH WHERE HINH_ANH.ID_SP = SAN_PHAM.ID_SP LIMIT 1) AS Hinh
                FROM 
                    SAN_PHAM 
                LEFT JOIN 
                    DANH_GIA 
                ON 
                    SAN_PHAM.ID_SP = DANH_GIA.ID_SP 
                WHERE 
                    SAN_PHAM.PhanLoai = ? AND SAN_PHAM.TrangThai != 'Đã xóa'
                GROUP BY 
                    SAN_PHAM.ID_SP, SAN_PHAM.TenSP, SAN_PHAM.Gia, SAN_PHAM.HinhThuc;";
        $sql2 = "SELECT 
                    SAN_PHAM.ID_SP, 
                    SAN_PHAM.TenSP, 
                    SAN_PHAM.Gia,
                    ROUND((SAN_PHAM.Gia*(1-SAN_PHAM.TyLeGiamGia))) AS GiaSauGiam,
                    ROUND(AVG(DANH_GIA.SoSao), 0) AS SoSao,
                    CASE 
                        WHEN SAN_PHAM.ID_SP IN (SELECT ID_SP FROM THICH WHERE UID = $uid) 
                        THEN 1 
                        ELSE 0 
                    END AS YeuThich,
                    (SELECT Anh FROM HINH_ANH WHERE HINH_ANH.ID_SP = SAN_PHAM.ID_SP LIMIT 1) AS Hinh
                FROM 
                    SAN_PHAM 
                LEFT JOIN 
                    DANH_GIA 
                ON 
                    SAN_PHAM.ID_SP = DANH_GIA.ID_SP 
                WHERE 
                    SAN_PHAM.PhanLoai = ? AND SAN_PHAM.TrangThai != 'Đã xóa'
                GROUP BY 
                    SAN_PHAM.ID_SP, SAN_PHAM.TenSP, SAN_PHAM.Gia, SAN_PHAM.HinhThuc;";
        if($login) $query = $this->conn->prepare($sql2);
        else $query = $this->conn->prepare($sql1);
        if (!$query) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
        $query->bind_param("s", $type);
        $query->execute();
        $result = $query->get_result();
    
        // Fetch all results as an associative array
        $data = $result->fetch_all(MYSQLI_ASSOC);
    
        return $data;
    }
}
?>