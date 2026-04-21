<?php

function csv_to_arr($filename) {
  $handle = fopen($filename, 'r');
  
  $headers = fgetcsv($handle);
  // remove UTF-8 BOM if exists
  $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
  $headers = array_map('trim', $headers);
  
  $data = [];

  while (($row = fgetcsv($handle)) !== FALSE) {
    $data[] = array_combine($headers, $row);
  }

  fclose($handle);
  return $data;
}
