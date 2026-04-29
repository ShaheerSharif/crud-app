<?php
include('../lib/jwt.php');

$conn = require_once __DIR__ . '/../config/db.php';

discard_jwt($conn);
header('Location: login.php');
