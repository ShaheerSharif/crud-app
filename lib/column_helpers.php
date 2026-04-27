<?php

function col_exists($conn, $table, $col) {
  $q = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = '$table'
    AND COLUMN_NAME = '$col'
  ");

  $row = $q->fetch_assoc();

  return $row && $row['cnt'] > 0;
}

function add_column($conn, $table, $col, $datatype='VARCHAR(255)', $nullable=true) {
  $col = mysqli_real_escape_string($conn, $col);

  $null_sql = $nullable ? 'NULL' : 'NOT NULL';

  mysqli_query($conn, "ALTER TABLE `$table` ADD COLUMN `$col` $datatype $null_sql");
}

function identify_relevant_table($conn, $col) {
  $aliasTable = [
    'users'    => ['user_', 'user'],
    'branches' => ['branch_', 'branch'],
    'areas'    => ['area_', 'area'],
    'regions'  => ['region_', 'region'],
  ];

  $relevantTable = null;

  foreach ($aliasTable as $table => $aliases) {
    foreach ($aliases as $a) {
      // check if column starts with alias
      if (strpos($col, $a) === 0) {
        $relevantTable = $table;
        break;
      }
    }

    if ($relevantTable) {
      break;
    }
  }

  if ($relevantTable) {
    return [
      'table' => $relevantTable,
      'exists' => col_exists($conn, $relevantTable, $col),
    ];
  }

  return [
    'table' => null,
    'exists' => false,
  ];
}
