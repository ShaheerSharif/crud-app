<?php

require_once __DIR__ . '/../lib/jwt.php';

$conn = require_once __DIR__ . '/../config/db.php';
$jwt_config = require_once __DIR__ . '/../config/jwt.php';

if (!isset($_COOKIE['token'])) {
  header('Location: ../auth/login.php');
  exit;
}

$payload = verify_jwt($conn, $jwt_config, $_COOKIE['token']);

if (!$payload) {
  discard_jwt($conn);
  header('Location: ../auth/login.php');
  exit;
}

return $payload;
