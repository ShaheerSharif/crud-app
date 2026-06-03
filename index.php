<?php

require_once __DIR__ . '/middleware/auth.php';

require_auth();

header('Location: /crud-app/users');
