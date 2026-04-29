<?php
include('../middleware/auth.php');

require_auth();

$conn = require_once '../config/db.php';

$id = $_GET['user_id'];

mysqli_query($conn, "UPDATE users SET user_isactive=0 WHERE user_id=$id");

header("Location: ./");
exit;
