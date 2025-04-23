<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/ProductService.php';

$db = new Database();
$model = new ProductService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['ID_SP'])) $ID_SP = $data['ID_SP'];
    else $ID_SP = '';
    if ($_SESSION["Role"] == 'Admin') $status = true; 
    else $status = false;
    echo json_encode($model->getInfo($ID_SP, $status));
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>