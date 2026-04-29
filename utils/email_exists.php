<?php
require_once __DIR__ . '/../lib/user.php';

$email = $_POST['email'];

$id = email_exists($email);

if ($id !== null) {
    echo json_encode([
        'exists' => true,
    ]);
} else {
    echo json_encode([
        'exists' => false,
    ]);
}
