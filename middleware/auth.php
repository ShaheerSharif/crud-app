<?php
include('../lib/jwt.php');

function require_auth() {
  $token = $_COOKIE['token'];

  if (!$token) {
    header('Location: ../auth/login.php');
    exit;
  }

  $payload = verify_jwt($token);

  if (!$payload) {
    discard_jwt();
    header('Location: ../auth/login.php');
    exit;
  }

  return $payload;
} 
