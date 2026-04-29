<?php

require_once __DIR__ . '/str_helpers.php';

/**
 * Convert CSV records to PHP arrays
 */
function csv_to_arr(string $file) {
  $handle = fopen($file, 'r');
  
  $headers = fgetcsv($handle);

  $headers[0] = remove_utf8_bom($headers[0]);
  $headers = array_map('normalize_str', $headers);
  
  $data = [];

  while (($row = fgetcsv($handle)) !== FALSE) {
    $data[] = array_combine($headers, $row);
  }

  fclose($handle);

  return [
    'headers' => $headers,
    'rows' => $data
  ];
}
