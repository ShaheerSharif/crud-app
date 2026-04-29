<?php
require_once __DIR__ . '/../lib/jwt.php';

if (!isset($_COOKIE['token'])) {
  return;
}

$payload = verify_jwt($_COOKIE['token']);

if (!$payload) {
  discard_jwt();
  return;
}

header('Location: /crud-app/users');
exit;
