<?php

require_once __DIR__ . '/bootstrap.php';

function get_jwt_config() {
  static $jwt_config = null;
  if (!$jwt_config) {
    $jwt_config = [
      'secret' => $_ENV['JWT_SECRET'],
    ];
  }
  return $jwt_config;
}
