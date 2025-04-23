<?php
require_once dirname(__DIR__, 2) . '/models/PayOsService.php';

$db = new Database();
$model = new PayOsService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $response = $model->check_payment($data['orderCode']);
    echo json_encode( $response);
}
else {
    $response = ['error' => 'Invalid request method'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
