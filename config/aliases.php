<?php

function get_aliases() {
  static $aliases = null;
  if (!$aliases) {
    $aliases = [
      'users'    => ['user_', 'user'],
      'branches' => ['branch_', 'branch'],
      'areas'    => ['area_', 'area'],
      'regions'  => ['region_', 'region'],
    ];
  }
  return $aliases;
}
