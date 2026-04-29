<?php

$payload = require_once __DIR__ . '/../middleware/auth.php';

$conn = require_once __DIR__ . '/../config/db.php';

$id = $_GET['user_id'];

mysqli_query($conn, "UPDATE users SET user_isactive=0 WHERE user_id=$id");

header("Location: ./");
exit;
