<?php
session_start();
class Database {
    public $conn;
    private $servername = "localhost";
    private $username = "root";
    private $db_name = "bkstore";
    private $db_password = "";

    public function __construct() {
        // Tạo kết nối
        $this->conn = new mysqli($this->servername, $this->username, $this->db_password, $this->db_name);

        // Kiểm tra kết nối
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function BaoTri() {
        $sql = "SELECT TrangThaiBaoTri FROM HE_THONG WHERE 1 LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['TrangThaiBaoTri'];
        } else {
            return false;
        }
    }
}

$db = new Database();
$TrangThaiBaoTri = $db->BaoTri();

?>
