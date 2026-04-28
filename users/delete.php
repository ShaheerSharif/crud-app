<?php
include("../config/db.php");
include('../middleware/auth.php');

require_auth();

$id = $_GET['user_id'];

mysqli_query($conn, "UPDATE users SET user_isactive=0 WHERE user_id=$id");

header("Location: ../");
exit;
