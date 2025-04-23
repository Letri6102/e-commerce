<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/SystemService.php';

$db = new Database();
$model = new SystemService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $response = $model->getContactInfo();
    echo json_encode($response);
}
else {
    $response = ['error' => 'Invalid request method'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>