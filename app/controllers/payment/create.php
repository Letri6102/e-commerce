<?php
require_once dirname(__DIR__, 2) . '/models/PayOsService.php';

$db = new Database();
$model = new PayOsService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $response = $model->create_payment($data['orderCode'],$data['amount'],$data['description'],$data['buyerName'],$data['buyerEmail'],$data['cancelUrl'],$data['returnUrl']);
    echo json_encode( $response);
}
else {
    $response = ['error' => 'Invalid request method'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
