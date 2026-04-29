<?php

require_once __DIR__ . '/bootstrap.php';

return [
    'secret' => $_ENV['JWT_SECRET'],
];
