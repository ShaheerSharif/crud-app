<?php

require_once __DIR__ . '/../middleware/auth.php';

$payload = require_auth();

require_once __DIR__ . '/../lib/user.php';

$id = $_GET['user_id'];

delete_user($id);

header("Location: /crud-app/users");
exit;
