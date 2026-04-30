<?php
require_once __DIR__ . '/../lib/jwt.php';

if (!token_exists_locally()) return;

$payload = verify_jwt();

if (!$payload) {
  discard_jwt();
  return;
}

header('Location: /crud-app/users');
exit;
