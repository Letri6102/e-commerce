<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
header('Content-Type: application/json');
session_unset();
session_destroy();
return ['success' => true, 'message' => 'Logout successful'];
?>
