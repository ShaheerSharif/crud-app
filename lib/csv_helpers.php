<?php

include('str_helpers.php');

/**
 * Convert CSV records to PHP arrays
 */
function csv_to_arr($filename) {
  $handle = fopen($filename, 'r');
  
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
