<?php

require_once __DIR__ . '/bootstrap.php';

function get_db() {
  static $conn = null;
  if (!$conn) {
    $conn = mysqli_connect(
      $_ENV['DB_HOST'],
      $_ENV['DB_USER'],
      $_ENV['DB_PASS'],
      $_ENV['DB_NAME']
    );
    if (!$conn) die("Connection failed: " . mysqli_connect_error());
  }
  return $conn;
}
