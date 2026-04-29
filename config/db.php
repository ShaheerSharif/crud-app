<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$db = getenv('DB_NAME');
$conn = mysqli_connect($host, $user, "", $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

return $conn;
