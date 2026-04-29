<?php
require_once __DIR__ . '/../lib/jwt.php';

if (!isset($_COOKIE['token'])) {
  header('Location: ../auth/login.php');
  exit;
}

$payload = verify_jwt($_COOKIE['token']);

if (!$payload) {
  discard_jwt();
  header('Location: ../auth/login.php');
  exit;
}

return $payload;
