<?php
include("array_helpers.php");

function create_user($conn, $user_email, $branch_id, $fields) {
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
    mysqli_query($conn, "
      INSERT INTO users(user_email, branch_id)
      VALUES (
        '$user_email',
        $branch_id
      )
    ");
  }

  else {
    mysqli_query($conn, "
      INSERT INTO users(user_email, branch_id, $sqlCols)
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

function update_user_all_except_email($conn, $user_id, $branch_id, $fields) {
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
    mysqli_query($conn, "UPDATE users SET branch_id=$branch_id WHERE user_id=$user_id");
  } else {
    mysqli_query($conn, "UPDATE users SET branch_id=$branch_id, $sql WHERE user_id=$user_id");
  }
}

function email_exists($conn, $user_email) {
  $q = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email='$user_email'");
  $res = $q->fetch_assoc();

  return $res['user_id'];
}
