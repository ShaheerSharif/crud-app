<?php
include("../config/db.php");

$email = $_POST['email'];

$q = mysqli_query($conn, "SELECT user_email from users WHERE user_email='$email'");

if ($q->num_rows > 0) {
    echo json_encode([
        'exists' => true,
    ]);
} else {
    echo json_encode([
        'exists' => false,
    ]);
}
