<?php

require_once __DIR__ . '/file_helpers.php';

class LogLevel {
  const INFO    = 'INFO';
  const DEBUG   = 'DEBUG';
  const WARNING = 'WARNING';
  const ERROR   = 'ERROR';
  const FATAL   = 'FATAL';
}

function sql_log(string $service, string $msg, array $data = [], string $loglevel = LogLevel::INFO) {
  $timestamp = gmdate('Y-m-d H:i:s');

  $dataStr = implode(', ', array_map(
    fn ($k, $v) => "$k: $v",
    array_keys($data),
    $data
  ));

  $dataStr = $dataStr ? '{' . $dataStr . '}' : '';
  $log = "[$timestamp] $loglevel - $service: $msg $dataStr" . PHP_EOL;

  append_to_file('../logs', 'sql.log', $log);
}
