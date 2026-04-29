<?php
include('../config/db.php');
include('../config/aliases.php');

function col_exists(string $table, string $col) {
  $conn = get_db();
  $q = $conn->query("SELECT COUNT(*) AS cnt
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = '$table'
    AND COLUMN_NAME = '$col'
  ");

  $row = $q->fetch_assoc();

  return $row && $row['cnt'] > 0;
}

function add_column(string $table, string $col, string $datatype='VARCHAR(255)', bool $nullable=true) {
  $conn = get_db();
  $col = $conn->real_escape_string($col);

  $null_sql = $nullable ? 'NULL' : 'NOT NULL';

  $conn->query("ALTER TABLE `$table` ADD COLUMN `$col` $datatype $null_sql");
}

function identify_relevant_table(string $col) {
  $aliasTable = get_aliases();

  foreach ($aliasTable as $table => $aliases) {
    foreach ($aliases as $a) {
      // check if column starts with alias
      if (strpos($col, $a) === 0) {
        return [
          'table' => $table,
          'exists' => col_exists($table, $col),
        ];
      }
    }
  }

  return [
    'table' => null,
    'exists' => false,
  ];
}
