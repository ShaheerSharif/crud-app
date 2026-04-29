<?php
include('../lib/jwt.php');

function require_auth(mysqli $conn) {
  $token = $_COOKIE['token'];

  if (!$token) {
    header('Location: ../auth/login.php');
    exit;
  }

  $payload = verify_jwt($conn, $token);

  if (!$payload) {
    discard_jwt($conn);
    header('Location: ../auth/login.php');
    exit;
  }

  return $payload;
} 
