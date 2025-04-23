<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/AuthService.php';

$db = new Database();
$model = new AuthService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['email'])) $email = $data['email'];
    else $email = '';
    $response = $model->forgotPassword($email);
    if ($response['status']) {
        echo json_encode([
            'success' => true,
            'message' => 'Reset password email sent',
            'user' => [
                'email' => $email,
                'password' => $response['password']
            ]
        ]);
    } else {
        echo json_encode( ['success' => false, 'message' => $response['message']] );
    }
}
else {
    $response = ['error' => 'Invalid request method'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
