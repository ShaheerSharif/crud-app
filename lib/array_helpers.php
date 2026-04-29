<?php

/**
 * Keeps all key value pairs in array except for excluded keys.
 */
function filter_all_keys_except(array $arr, array $exclude) {
  if (empty($exclude)) return $arr;

  return array_diff_key($arr, array_flip($exclude));
}

/**
 * Keeps all keys starting with the specified prefix.
 */
function filter_keys_starting_with(array $arr, string $prefix) {
  return array_filter($arr, function ($key) use ($prefix) {
    return strpos($key, $prefix) === 0;
  }, ARRAY_FILTER_USE_KEY);
}
