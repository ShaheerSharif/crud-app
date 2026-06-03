<?php

require_once __DIR__ . '/bootstrap.php';

function get_app_env() {
  return $_ENV['APP_ENV']; // prod or dev
}
