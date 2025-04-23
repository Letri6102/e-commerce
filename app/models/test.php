<?php
// Include tệp cấu hình cơ sở dữ liệu
include '../../config/db.php';

$db = new Database();
$conn = $db->conn;
// Viết truy vấn SQL
$sql = "SELECT * FROM LOGIN";
$result = $conn->query($sql);

// Kiểm tra và xuất kết quả
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "uid: " . $row["UID"]. " - Name: " . $row["Ten"]. "<br>";
    }
} else {
    echo "0 results";
}

// Đóng kết nối
$conn->close();
?>