<?php
include('../lib/jwt.php');

discard_jwt();
header('Location: login.php');
