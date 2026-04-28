<?php

setcookie('token', '', time() - (3600 * 24 * 30), '/');
unset($_COOKIE['token']);

header('Location: login.php');
