<?php
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 1) . '/models/support.php';

class SystemService {
    private $conn;
    private $support;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->support = new support();
    }

    public function getNewInfo() {
        // Query to fetch MaTinTuc and TieuDe
        $sql1 = "SELECT MaTinTuc, TieuDe FROM TIN_TUC WHERE TrangThai != 'Đang ẩn' ORDER BY MaTinTuc DESC";
        $query1 = $this->conn->prepare($sql1);
        $query1->execute();
        $result1 = $query1->get_result();
    
        $finalResults = [];
        while ($row = $result1->fetch_assoc()) {
            $maTinTuc = $row['MaTinTuc'];
            $tieuDe = $row['TieuDe'];
    
            // Query to fetch LinkAnh based on MaTinTuc
            $sql2 = "SELECT LinkAnh FROM ANH_MINH_HOA WHERE MaTinTuc = ?";
            $query2 = $this->conn->prepare($sql2);
            $query2->bind_param('i', $maTinTuc);
            $query2->execute();
            $result2 = $query2->get_result();
    
            $tmp = [];
            while ($row2 = $result2->fetch_assoc()) {
                $tmp[] = $row2;
            }
    
            // Add the results to the final array
            $finalResults[] = [
                'MaTinTuc' => $maTinTuc,
                'TieuDe' => $tieuDe,
                'AnhMinhHoa' => $tmp
            ];
        }
    
        return $finalResults;
    }
    
    public function getContactInfo() {
        $sql = "SELECT MaThongTin, Loai, ThongTin, HinhAnh FROM THONG_TIN_LIEN_HE WHERE TrangThai != 'Đang ẩn' ORDER BY MaThongTin DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ['success' => false, 'message' => 'Không có thông tin liên hệ'];
        }
        $contact = [];
        while ($row = $result->fetch_assoc()) {
            $contact[] = [
                'ma_thong_tin' => $row['MaThongTin'],
                'loai' => $row['Loai'],
                'thong_tin' => $row['ThongTin'],
                'hinh_anh' => $row['HinhAnh']
            ];
        }
        return ['success' => true, 'info' => $contact];
    }

    public function getSocialInfo() {
        $sql = "SELECT MaMXH, HinhAnh, LienKet FROM MANG_XA_HOI WHERE TrangThai != 'Đang ẩn' ORDER BY MaMXH DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = [
                'MaMXH' => $row['MaMXH'],
                'Image' => $row['HinhAnh'],
                'Link' => $row['LienKet']
            ];
        }
        return $result;
    }

    public function getPartnerInfo() {
        $sql = "SELECT MaDoiTac, Ten, HinhAnh, LienKet FROM DOI_TAC WHERE TrangThai != 'Đang ẩn' ORDER BY MaDoiTac DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = [
                'MaDoiTac' => $row['MaDoiTac'],
                'name' => $row['Ten'],
                'image' => $row['HinhAnh'],
                'link' => $row['LienKet']
            ];
        }
        return $result;
    }

    public function getNew($MaTinTuc) {
        $sql = 'SELECT * FROM TIN_TUC WHERE MaTinTuc = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaTinTuc);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        $result['Anh'] = $this->getImageNews($MaTinTuc);
        return $result;
    }

    public function footer() {
        return [
            'mang_xa_hoi' => $this->getSocialInfo(),
            'doi_tac' => $this->getPartnerInfo(),
            'thong_tin_lien_he' => $this->getContactInfo()
        ];
    }

    public function getNewsList() {
        $sql = 'SELECT * FROM tin_tuc ORDER BY MaTinTuc DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $row['Anh'] = $this->getImageNews($row['MaTinTuc']);
            $result[] = [
                'MaTinTuc' => $row['MaTinTuc'],
                'TieuDe' => $row['TieuDe'],
                'Anh' => $row['Anh'],
                'NoiDung' => $row['NoiDung'],
                'ThoiGianTao' => $row['ThoiGianTao'],
                'TuKhoa' => $row['TuKhoa'],
                'MoTa' => $row['MoTa'],
                'TrangThai' => $row['TrangThai']
            ];
        }
        return $result;
    }

    public function setNews($TieuDe, $MoTa, $NoiDung, $TuKhoa) {
        $time = $this->support->startTime();
        $sql = "INSERT INTO TIN_TUC (TieuDe, ThoiGianTao, NoiDung, TuKhoa, MoTa) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $TieuDe, $time, $NoiDung, $TuKhoa, $MoTa);
        $stmt->execute();
        return ['success' => true, 'news_id' => mysqli_insert_id($this->conn)];
    }

    public function setImageNews($newsPath, $MaTinTuc, $MoTaHinhAnh) {
        $sql = "INSERT INTO ANH_MINH_HOA (MaTinTuc, LinkAnh, MoTa) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $MaTinTuc, $newsPath, $MoTaHinhAnh);
        $stmt->execute();
        return ['success' => true, 'message' => 'Thêm tin tức thành công'];
    }

    public function deleteNews($MaTinTuc) {
        $sql = 'DELETE FROM TIN_TUC WHERE MaTinTuc = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaTinTuc);
        $stmt->execute();
        return true;
    }

    public function deleteImageNews($MaTinTuc) {
        $sql = 'DELETE FROM ANH_MINH_HOA WHERE MaTinTuc = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaTinTuc);
        $stmt->execute();
        return true;
    }

    public function updateNews($TieuDe, $MoTa, $NoiDung, $TuKhoa, $MaTinTuc, $TrangThai) {
        $rac = $this->getNew($MaTinTuc);
        
        $TieuDe = $TieuDe != null ? $TieuDe : $rac['TieuDe'];
        $MoTa = $MoTa != null ? $MoTa : $rac['MoTa'];
        $NoiDung = $NoiDung != null ? $NoiDung : $rac['NoiDung'];
        $TuKhoa = $TuKhoa != null ? $TuKhoa : $rac['TuKhoa'];
        $TrangThai = ($TrangThai == 'Đang ẩn' || $TrangThai == 'Đang hiện') ? $TrangThai : $rac['TrangThai'];

        $sql = "UPDATE TIN_TUC SET TieuDe = ?, MoTa = ?, NoiDung = ?, TuKhoa = ?, TrangThai = ? WHERE MaTinTuc = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $TieuDe,  $MoTa, $NoiDung, $TuKhoa, $TrangThai, $MaTinTuc);
        $stmt->execute();
        return true;
    }

    public function updateNewsImage($AnhMuonXoa, $MoTaHinhAnh, $MaTinTuc) {
        $del = $this->deleteImageNew($MaTinTuc, $AnhMuonXoa);
        $uploadDir = dirname(__DIR__, 2) . "/public/image/new/$MaTinTuc/";
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
                            $result = $this->setImageNews("/public/image/new/$MaTinTuc/" . $newsFileName, $MaTinTuc, $MoTaHinhAnh[$i]);
                            $uploaded = true;
                        }
                    }
                }
            }
            if (!$uploaded) return false;
            else if ($del) return $result['success'];
            else return false;
        }
        return $del;
    }

    public function getBannerList() {
        $sql = 'SELECT * FROM BANNER ORDER BY MaBanner DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $tenSP = $this->getNameBanner($row['IdSP']);
            $result[] = [
                'MaBanner' => $row['MaBanner'],
                'Image' => $row['Image'],
                'IdSP' => $row['IdSP'],
                'MoTa' => $row['MoTa'],
                'TenSP' => $tenSP,
                'TrangThai' => $row['TrangThai']
            ];
        }
        return $result;
    }

    public function setBanner($IdSP, $MoTa) {
        $sql = "INSERT INTO BANNER (IdSP, MoTa) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $IdSP, $MoTa);
        $stmt->execute();
        return ['success' => true, 'banner_id' => mysqli_insert_id($this->conn)];
    }

    public function updateBanner($MaBanner, $Image, $IdSP, $MoTa, $TrangThai) {
        $rac = $this->getBanner($MaBanner);
        
        $Image = $Image != null ? $Image : $rac['Image'];
        $MoTa = $MoTa != null ? $MoTa : $rac['MoTa'];
        $IdSP = $IdSP != null ? $IdSP : $rac['IdSP'];
        $TrangThai = ($TrangThai == 'Đang ẩn' || $TrangThai == 'Đang hiện') ? $TrangThai : $rac['TrangThai'];

        $sql = "UPDATE BANNER SET Image = ?, MoTa = ?, IdSP = ?, TrangThai = ? WHERE MaBanner = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssisi", $Image,  $MoTa, $IdSP, $TrangThai, $MaBanner);
        $stmt->execute();
        return true;
    }

    public function getBanner($MaBanner) {
        $sql = 'SELECT * FROM BANNER WHERE MaBanner = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaBanner);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteImageBanner($MaBanner) {
        $Anh = $this->getBanner($MaBanner)['Image'];
        if ($Anh != null) {
            $filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($Anh, '/'));
            chmod($filePath, 0777);
            unlink($filePath);
        }
        return;
    }

    public function deleteBanner($MaBanner) {
        $sql = 'DELETE FROM BANNER WHERE MaBanner = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaBanner);
        $stmt->execute();
        return true;
    }

    public function getContactList() {
        $sql = 'SELECT * FROM THONG_TIN_LIEN_HE ORDER BY MaThongTin DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = [
                'MaThongTin' => $row['MaThongTin'],
                'Anh' => $row['HinhAnh'],
                'Loai' => $row['Loai'],
                'ThongTin' => $row['ThongTin'],
                'TrangThai' => $row['TrangThai']
            ];
        }
        return $result;
    }

    public function setContact($Loai, $ThongTin) {
        $sql = "INSERT INTO THONG_TIN_LIEN_HE (Loai, ThongTin) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $Loai, $ThongTin);
        $stmt->execute();
        return ['success' => true, 'contact_id' => mysqli_insert_id($this->conn)];
    }

    public function updateContact($MaContact, $Image, $Loai, $ThongTin, $TrangThai) {
        $rac = $this->getContact($MaContact);
        
        $Image = $Image != null ? $Image : $rac['HinhAnh'];
        $Loai = $Loai != null ? $Loai : $rac['Loai'];
        $ThongTin = $ThongTin != null ? $ThongTin : $rac['ThongTin'];
        $TrangThai = ($TrangThai == 'Đang ẩn' || $TrangThai == 'Đang hiện') ? $TrangThai : $rac['TrangThai'];

        $sql = "UPDATE THONG_TIN_LIEN_HE SET HinhAnh = ?, Loai = ?, ThongTin = ?, TrangThai = ? WHERE MaThongTin = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $Image,  $Loai, $ThongTin, $TrangThai, $MaContact);
        $stmt->execute();
        return true;
    }

    public function getContact($MaThongTin) {
        $sql = 'SELECT * FROM THONG_TIN_LIEN_HE WHERE MaThongTin = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaThongTin);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteImageContact($MaContact) {
        $Anh = $this->getContact($MaContact)['HinhAnh'];
        if ($Anh != null) {
            $filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($Anh, '/'));
            chmod($filePath, 0777);
            unlink($filePath);
        }
        return;
    }

    public function deleteContact($MaContact) {
        $sql = 'DELETE FROM THONG_TIN_LIEN_HE WHERE MaThongTin = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaContact);
        $stmt->execute();
        return true;
    }

    public function getSocialList() {
        $sql = 'SELECT * FROM mang_xa_hoi ORDER BY MaMXH DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = [
                'MaMXH' => $row['MaMXH'],
                'Image' => $row['HinhAnh'],
                'Link' => $row['LienKet'],
                'TrangThai' => $row['TrangThai']
            ];
        }
        return $result;
    }

    public function setSocial($Lienket) {
        $sql = "INSERT INTO MANG_XA_HOI (Lienket) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $Lienket);
        $stmt->execute();
        return ['success' => true, 'social_id' => mysqli_insert_id($this->conn)];
    }

    public function updateSocial($MaMXH, $HinhAnh, $LienKet, $TrangThai) {
        $rac = $this->getSocial($MaMXH);
        
        $HinhAnh = $HinhAnh != null ? $HinhAnh : $rac['HinhAnh'];
        $LienKet = $LienKet != null ? $LienKet : $rac['LienKet'];
        $TrangThai = ($TrangThai == 'Đang ẩn' || $TrangThai == 'Đang hiện') ? $TrangThai : $rac['TrangThai'];

        $sql = "UPDATE MANG_XA_HOI SET HinhAnh = ?, LienKet = ?, TrangThai = ? WHERE MaMXH = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $HinhAnh, $LienKet, $TrangThai, $MaMXH);
        $stmt->execute();
        return true;
    }

    public function getSocial($MaMXH) {
        $sql = 'SELECT * FROM MANG_XA_HOI WHERE MaMXH = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaMXH);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteImageSocial($MaMXH) {
        $Anh = $this->getSocial($MaMXH)['HinhAnh'];
        if ($Anh != null) {
            $filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($Anh, '/'));
            chmod($filePath, 0777);
            unlink($filePath);
        }
        return;
    }

    public function deleteSocial($MaMXH) {
        $sql = 'DELETE FROM MANG_XA_HOI WHERE MaMXH = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaMXH);
        $stmt->execute();
        return true;
    }

    public function getPartnerList() {
        $sql = 'SELECT * FROM doi_tac ORDER BY MaDoiTac DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = [
                'MaDoiTac' => $row['MaDoiTac'],
                'Ten' => $row['Ten'],
                'image' => $row['HinhAnh'],
                'link' => $row['LienKet'],
                'TrangThai' => $row['TrangThai']
            ];
        }
        return $result;
    }

    public function setPartner($LienKet, $Ten) {
        $sql = "INSERT INTO DOI_TAC (Lienket, Ten) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $LienKet, $Ten);
        $stmt->execute();
        return ['success' => true, 'partner_id' => mysqli_insert_id($this->conn)];
    }

    public function updatePartner($MaDoiTac, $HinhAnh, $LienKet, $Ten, $TrangThai) {
        $rac = $this->getPartner($MaDoiTac);
        
        $HinhAnh = $HinhAnh != null ? $HinhAnh : $rac['HinhAnh'];
        $LienKet = $LienKet != null ? $LienKet : $rac['LienKet'];
        $TrangThai = ($TrangThai == 'Đang ẩn' || $TrangThai == 'Đang hiện') ? $TrangThai : $rac['TrangThai'];
        $Ten = $Ten != null ? $Ten : $rac['Ten'];

        $sql = "UPDATE DOI_TAC SET HinhAnh = ?, LienKet = ?, Ten = ?, TrangThai = ? WHERE MaDoiTac = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $HinhAnh, $LienKet, $Ten, $TrangThai, $MaDoiTac);
        $stmt->execute();
        return true;
    }

    public function getPartner($MaDoiTac) {
        $sql = 'SELECT * FROM DOI_TAC WHERE MaDoiTac = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaDoiTac);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteImagePartner($MaDoiTac) {
        $Anh = $this->getPartner($MaDoiTac)['HinhAnh'];
        if ($Anh != null) {
            $filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($Anh, '/'));
            chmod($filePath, 0777);
            unlink($filePath);
        }
        return;
    }

    public function deletePartner($MaDoiTac) {
        $sql = 'DELETE FROM DOI_TAC WHERE MaDoiTac = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaDoiTac);
        $stmt->execute();
        return true;
    }

    public function getSystem() {
        $sql = 'SELECT * FROM HE_THONG';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }

    public function updateSystem($MaHeThong, $TuKhoa, $ClientID, $APIKey, $Checksum, $TrangThaiBaoTri) {
        $rac = $this->getSystemById($MaHeThong);
        
        $TuKhoa = $TuKhoa != null ? $TuKhoa : $rac['TuKhoa'];
        $ClientID = $ClientID != null ? $ClientID : $rac['ClientID'];
        $APIKey = $APIKey != null ? $APIKey : $rac['APIKey'];
        $Checksum = $Checksum != null ? $Checksum : $rac['Checksum'];
        $TrangThaiBaoTri = $TrangThaiBaoTri != -1 ? $TrangThaiBaoTri : $rac['TrangThaiBaoTri'];

        $sql = "UPDATE HE_THONG SET TuKhoa = ?, ClientID = ?, APIKey = ?, `Checksum` = ?, TrangThaiBaoTri = ? WHERE MaHeThong = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssii", $TuKhoa, $ClientID, $APIKey, $Checksum, $TrangThaiBaoTri, $MaHeThong);
        $stmt->execute();
        return true;
    }



    private function getSystemById($MaHeThong) {
        $sql = 'SELECT * FROM HE_THONG WHERE MaHeThong = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $MaHeThong);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function countUser() {
        $sql = 'SELECT COUNT(*) as count FROM `LOGIN`';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    public function countOrder() {
        $sql = 'SELECT COUNT(*) as count, SUM(HoaDon) as hoadon FROM DON_HANG';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        return [
            'count' => $result['count'],
            'revenue' => intval($result['hoadon'])
        ];
    }

    public function countPropose() {
        $sql = 'SELECT COUNT(*) as count FROM SAN_PHAM_DE_XUAT';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    private function getNameBanner($IdSP) {
        $sql = "SELECT TenSP FROM san_pham WHERE ID_SP =?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $IdSP);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['TenSP'];
    }

    private function deleteImageNew($MaTinTuc, $AnhMuonXoa) {
        foreach ($AnhMuonXoa as $Anh) {
            $filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($Anh, '/'));
            chmod($filePath, 0777);
            unlink($filePath);
            $sql = "DELETE FROM ANH_MINH_HOA WHERE MaTinTuc = ? AND LinkAnh = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $MaTinTuc, $Anh);
            $stmt->execute();
        }
        return true;
    }

    private function getImageNews($MaTinTuc) {
        $sql = "SELECT LinkAnh FROM ANH_MINH_HOA WHERE MaTinTuc = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $MaTinTuc);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row['LinkAnh'];
        }
        return $result;
    }
}
?>
