<?php
require_once dirname(__DIR__, 1) . '/models/support.php';

class AuthService {
    private $conn;
    private $support;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->support = new support();
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM `LOGIN` WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Tài khoản không tồn tại'];
        }
        $user = $result->fetch_assoc();
        if (!password_verify($password, $user['Password'])) {
            return ['success' => false, 'message' => 'Sai mật khẩu'];
        }
        $stmt = $this->conn->prepare("SELECT * FROM KHACH_HANG WHERE `UID` = ? AND TrangThai = 'Đang hoạt động'");
        $stmt->bind_param("i", $user['UID']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Tài khoản đã bị ban'];
        }
        $ip = $this->getIPAddress();
        $thoi_gian = $this->support->startTime();
        $uid = $user['UID'];
        $nhat_ky = "INSERT INTO LICH_SU_DANG_NHAP (`UID`, ThoiGian, NoiDung) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($nhat_ky);
        $noi_dung = "Đã đăng nhập tài khoản IP: $ip";
        $stmt->bind_param("iss", $uid, $thoi_gian, $noi_dung);
        $stmt->execute();
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;
        $_SESSION["uid"] = $user['UID'];
        $_SESSION["Ten"] = $user['Ten'];
        $_SESSION["Role"] = $user['Role'];
        $_SESSION["Avatar"] = '';
        $avatar = $user['Avatar'];
        if ($avatar == null) {
            $_SESSION["Avatar"] = "https://ui-avatars.com/api/?background=random&name=" . urlencode($user['Ten']);
        }else{
            $_SESSION["Avatar"] = "/".$avatar; 
        }

        $stmt = $this->conn->prepare("SELECT * FROM `KHACH_HANG` WHERE `UID` = ?");
        $stmt->bind_param("s", $user['UID']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $_SESSION["SDT"] = $row['SDT'];
            $_SESSION["DiaChi"] = $row['DiaChi'];
        }


        return [
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'user' => [
                'email' => $email,
                'password' => $password
            ]
        ];
    }

    public function signup($name, $email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email không hợp lệ'];
        }
        $checkEmailQuery = $this->conn->prepare("SELECT * FROM `LOGIN` WHERE email = ?");
        $checkEmailQuery->bind_param("s", $email);
        $checkEmailQuery->execute();
        $result = $checkEmailQuery->get_result();
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Tài khoản đã được đăng ký'];
        }
        if (empty($password)) {
            return ['success' => false, 'message' => 'Vui lòng nhập mật khẩu'];
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `LOGIN` (ten, email, `password`, `role`) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $role = "Customer";
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
        $stmt->execute();
        $stmt = $this->conn->prepare("SELECT * FROM `LOGIN` WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $uid = $user['UID'];
        $stmt = $this->conn->prepare("INSERT INTO `KHACH_HANG` (`UID`) VALUES (?)");
        $stmt->bind_param("s", $uid);
        $stmt->execute();        
        return [
            'success' => true,
            'message' => 'Đăng ký thành công',
            'user' => [
                'email' => $email,
                'password' => $password
            ]
        ];
    }

    public function forgotPassword($email) {
        $stmt = $this->conn->prepare("SELECT * FROM `LOGIN` WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['status' => false, 'message'=> 'Tài khoản không tồn tại'];
        }
        $newPassword = $this->generateRandomPassword();
        $sent = $this->sendPasswordResetEmail($email, $newPassword);
        $this->updatePassword($email, $newPassword);
        if ($sent['status']) {
            return ['status' => true, 'password' => $newPassword];
        } else {
            return ['status' => false, 'message' => $sent['message']];
        }
    }

    public function changePassword($email, $current_password, $new_password) {
        $stmt = $this->conn->prepare("SELECT * FROM `LOGIN` WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($current_password, $user['Password'])) {
                $this->updatePassword($email, $new_password);
                return ['status' => true, 'password' => $new_password];
            } else {
                return ['status' => false, 'error' => 'Sai mật khẩu'];
            }
        }
        return ['status' => false, 'error' => 'Người dùng chưa có tài khoản'];
    }

    private function generateRandomPassword() {
        $length = 12;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $password;
    }

    function sendPasswordResetEmail($email, $newPassword) {
        $url = 'https://ttdev.id.vn/send.php';
        $data = json_encode([
            'email' => $email,
            'mess' => "Mật khẩu mới của bạn là: $newPassword",
        ]);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));
    
        $response = curl_exec($ch);
        curl_close($ch);
        $responseData = json_decode($response, true);
        if ($responseData['status'] === 'success') {
            return [
                'status' => true,
                'email' => $email,
                'password' => $newPassword
            ];
        } else {
            return [
                'status' => false,
                'message' => $responseData
            ];
        }
    }
    
    private function updatePassword($email, $newPassword) {
        $sql = "UPDATE `LOGIN` SET `Password` = ? WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();
    }

    private function getIPAddress() {
        $ip = $_SERVER['REMOTE_ADDR'];

        if ($ip === '::1') {
            $ip = '127.0.0.1';
        }
        return $ip;
    }
}
?>
