<?php
require_once __DIR__ . '/../lib/jwt.php';

function require_auth() {
  if (!token_exists_locally()) {
    header('Location: /crud-app/auth/login.php');
    exit;
  }
  
  $payload = verify_jwt();
  
  if (!$payload) {
    discard_jwt();
    header('Location: /crud-app/auth/login.php');
    exit;
  }
  
  return $payload;
}
