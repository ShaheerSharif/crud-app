<?php

/**
 * Normalize string. Remove numbers and special characters. Replace spaces with underscores.
 */
function normalize_str(string $str) {
  $str = trim($str);
  $str = strtolower($str);
  $str = preg_replace('/\s+/', '_', $str); // replace whitespace with single underscore
  $str = preg_replace('/[^a-z0-9_]/', '', $str);
  return $str;
}

/**
 * Removes UTF-8 BOM characters which may cause problems with PHP parsing.
 */
function remove_utf8_bom(string $str) {
  $str = preg_replace('/^\xEF\xBB\xBF/', '', $str);
  return $str;
}
