<?php
require_once dirname(__DIR__, 1) . '/models/support.php';

class CartService {
    private $conn;
    private $support;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->support = new support();
    }

    public function get($uid) {
        $sql = "SELECT * FROM TRONG_GIO_HANG WHERE `UID` = ? ORDER BY ID_SP DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Chưa thêm sản phẩm vào giỏ hàng'];
        }
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $res = [];
        foreach ($products as $product) {
            $info = $this->getInfo($product['ID_SP']);
            $image = $this->getImage($product['ID_SP']);
            $res[] = [
                'id' => $product['ID_SP'],
                'hinh_anh' => $image,
                'ten' => $info['ten'],
                'gia_goc' => $info['gia_goc'],
                'gia_khuyen_mai' => $info['gia_khuyen_mai'],
                'so_luong' => $product['SoLuong']
            ];
        }
        return ['success' => true, 'danh_sach_san_pham' => $res];
    }

    public function set($id, $product_id, $quantity) {
        $sql = "SELECT * FROM SAN_PHAM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không tìm thấy sản phẩm'];
        }
        $row = $result->fetch_assoc();
        if ($row['SoLuongKho'] < $quantity) {
            return ['success'=> false, 'message' => 'Trong kho không đủ sản phẩm'];
        }
        $check = "SELECT SoLuong FROM TRONG_GIO_HANG WHERE ID_SP = ? AND `UID` = ?";
        $stmt = $this->conn->prepare($check);
        $stmt->bind_param("ii", $product_id, $id);
        $stmt->execute();
        $result = $stmt->get_result();     
        if ($result->num_rows === 0) {
            $cart = "INSERT INTO TRONG_GIO_HANG (`UID`, ID_SP, SoLuong) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($cart);
            $stmt->bind_param("iii", $id, $product_id, $quantity);
            $stmt->execute();
        }
        else $this->update($id, $product_id, $result->fetch_assoc()['SoLuong'] + $quantity);
        return ['success' => true, 'message' => 'Thêm sản phẩm vào giỏ hàng thành công'];
    }

    public function remove($uid, $product_id) {
        $sql_check = "SELECT * FROM TRONG_GIO_HANG WHERE ID_SP = ? AND `UID` = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $product_id, $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows === 0) {
            return ['success' => false, 'message' => 'Chưa thêm sản phẩm vào giỏ hàng'];
        }
        $check = "DELETE FROM TRONG_GIO_HANG WHERE ID_SP = ? AND `UID` = ?";
        $stmt = $this->conn->prepare($check);
        $stmt->bind_param("ii", $product_id, $uid);
        $stmt->execute();
        return ['success' => true, 'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công'];
    }

    public function update($uid, $product_id, $quantity) {
        $sql_check = "SELECT SoLuong FROM TRONG_GIO_HANG WHERE ID_SP = ? AND `UID` = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $product_id, $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows === 0) {
            return ['success' => false, 'message' => 'Chưa thêm sản phẩm vào giỏ hàng'];
        }
        if ($this->checkCount($product_id, $quantity) == false) return ['success' => false, 'message' => 'Không đủ sảm phẩm trong kho'] ;
        if ($quantity > 0) {
            $sql_update = "UPDATE TRONG_GIO_HANG SET SoLuong = ? WHERE `ID_SP` = ? AND `UID` = ?";
            $stmt = $this->conn->prepare($sql_update);
            $stmt->bind_param("iii", $quantity, $product_id, $uid);
            $stmt->execute();
        }
        else $this->remove($uid, $product_id);
        return ['success' => true, 'message' => 'Cập nhật đơn hàng thành công'];
    }

    private function checkCount($ID_SP, $quantity) {
        $sql = 'SELECT SoLuongKho FROM SAN_PHAM WHERE `ID_SP` = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $ID_SP);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->fetch_assoc()['SoLuongKho'] < $quantity) return false;
        return true;
    }

    private function getInfo($id) {
        $sql = "SELECT * FROM SAN_PHAM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không tìm thấy sản phẩm'];
        }
        $product = $result->fetch_assoc();  
        return [
            'ten' => $product['TenSP'],
            'gia_goc' => intval($product['Gia']),
            'gia_khuyen_mai' => round($product['Gia'] - $product['Gia'] * $product['TyLeGiamGia'], 2)
        ];  
    }

    private function getImage($id) {
        $sql = "SELECT * FROM HINH_ANH WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            $anh = $result->fetch_assoc();
            return $anh['Anh'];
        }
        return '';
    }
}
?>