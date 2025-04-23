<?php
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 1) . '/models/OrderService.php';

class PayOsService {
    private $conn;
    private $order;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->conn->set_charset('utf8mb4');
        $this->order = new OrderService($conn);
    }

    public function create_payment($orderCode, $amount, $description, $buyerName, $buyerEmail, $cancelUrl, $returnUrl){
        if(!isset($orderCode) || !isset($amount) || !isset($description) ||  !isset($buyerName) ||  !isset($buyerEmail) || 
            !isset($cancelUrl) ||  !isset($returnUrl)){
                return [
                    'status' => false,
                    'message' => "Thiếu dữ liệu đầu vào"
                ];
        }

        $sql = "SELECT * FROM HE_THONG";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $clientid = $row['ClientID'];
        $apikey = $row['APIKey'];
        $checksum = $row['Checksum'];

        if($clientid == '' || $apikey == '' || $checksum == ''){
            return [
                'status' => false,
                'message' => "Lỗi cấu hình hệ thống"
            ];
        }

        $url = 'https://payos-i4bq.onrender.com/payment/create_payment';
        $data = json_encode([
            "orderCode" => $orderCode,
            "amount"    => $amount,
            "description"   =>  $description,
            "buyerName" =>  $buyerName,
            "buyerEmail"    => $buyerEmail,
            "cancelUrl" =>  $cancelUrl,
            "returnUrl" =>  $returnUrl,
            "clientid"  => $clientid,
            "apikey"    => $apikey,
            "checksum"  => $checksum
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
        return $responseData;
    }

    public function check_payment($orderCode): mixed{
        if(!isset($orderCode)){
                return [
                    'status' => false,
                    'message' => "Thiếu dữ liệu đầu vào"
                ];
        }

        $sql = "SELECT * FROM HE_THONG";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $clientid = $row['ClientID'];
        $apikey = $row['APIKey'];
        $checksum = $row['Checksum'];

        if($clientid == '' || $apikey == '' || $checksum == ''){
            return [
                'status' => false,
                'message' => "Lỗi cấu hình hệ thống"
            ];
        }

        $url = "https://payos-i4bq.onrender.com/payment/get_payment";

        $data = json_encode([
            "id" => $orderCode,
            "clientid"  => $clientid,
            "apikey"    => $apikey,
            "checksum"  => $checksum
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

        if($responseData['status'] == 'CANCELLED'){
            // huỷ đơn hàng
            $this->order->update('Đã huỷ','Huỷ thanh toán', $orderCode);
            
        }else if ($responseData['status'] == 'PAID'){
            $this->order->update('Đã xác nhận','Đã thanh toán', $orderCode);
        }

        return $responseData;
    }
}
?>