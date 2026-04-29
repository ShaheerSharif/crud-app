<?php

$payload = require_once __DIR__ . '/../middleware/auth.php';

require_once __DIR__ . '/../lib/user.php';

$id = $_GET['user_id'];

delete_user($id);

header("Location: ./");
exit;
