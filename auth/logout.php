<?php
require_once __DIR__ . '/../lib/jwt.php';

discard_jwt();
header('Location: login.php');
