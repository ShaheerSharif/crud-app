<?php

/**
 * Creates file at some path if it does not exist. Creates directories recursively if they don't exist as well.
 */
function create_file_if_not_exist($path, $filename, $perm) {
  if (!is_dir($path)) {
    if (!mkdir($path, $perm, true)) {
      die('Failed to create folder');
    }
  }

  $file = $path . '/' . $filename;

  if (!file_exists($file)) {
    $content = '';
    if (!file_put_contents($file, $content) === false) {
      die('Failed to create file');
    }
  }
}

function append_to_file($path, $filename, $content, $perm=0755) {
  $file = $path . '/' . $filename;

  create_file_if_not_exist($path, $filename, $perm);
  file_put_contents($file, $content, FILE_APPEND);
}
