<?php
include("array_helpers.php");

function create_user(mysqli $conn, string $user_email, int $branch_id, array $fields) {
  $prefix = 'user_';
  $exclude = ['user_id', 'user_email'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlColParts = [];
  $sqlValParts = [];

  foreach ($fields as $key => $val) {
    $sqlColParts[] = "`$key`";
    $sqlValParts[] = "'$val'";
  }

  $sqlCols = implode(', ', $sqlColParts);
  $sqlVals = implode(', ', $sqlValParts);

  if ($sqlCols === '' || $sqlVals === '') {
    $conn->query("INSERT INTO users(user_email, branch_id)
      VALUES (
        '$user_email',
        $branch_id
      )
    ");
  }

  else {
    $conn->query("INSERT INTO users(user_email, branch_id, $sqlCols)
      VALUES (
        '$user_email',
        $branch_id,
        $sqlVals
      )
    ");
  }
  
  $user_id = mysqli_insert_id($conn);

  return $user_id;
}

function update_user_all_except_email(mysqli $conn, int $user_id, int $branch_id, array $fields) {
  $prefix = 'user_';
  $exclude = ['user_id', 'user_email'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlParts = [];

  foreach ($fields as $key => $val) {
    $sqlParts[] = "`$key`='$val'";
  }

  $sql = implode(', ', $sqlParts);

  if ($sql === '') {
    $conn->query("UPDATE users SET branch_id=$branch_id WHERE user_id=$user_id");
  } else {
    $conn->query("UPDATE users SET branch_id=$branch_id, $sql WHERE user_id=$user_id");
  }
}

function email_exists(mysqli $conn, string $user_email) {
  $q = $conn->query("SELECT user_id FROM users WHERE user_email='$user_email' LIMIT 1");
  $res = $q->fetch_assoc();

  return $res['user_id'];
}
