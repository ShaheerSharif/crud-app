<?php

function get_base_url(): string {
  $host = $_SERVER['HTTP_HOST']; // includes hostname and port
  $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
  return $scheme . '://' . $host;
}
