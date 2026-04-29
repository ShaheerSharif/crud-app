<?php
$host = "localhost";
$user = "root";
$db = "crud_app";
$conn = mysqli_connect($host, $user, "", $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

return $conn;
