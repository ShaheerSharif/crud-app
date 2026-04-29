<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/array_helpers.php';

function create_user(string $user_email, int $branch_id, array $fields) {
  $conn = get_db();
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

function update_user_all_except_email(int $user_id, int $branch_id, array $fields) {
  $conn = get_db();
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

function update_user(int $user_id, int $branch_id, array $fields) {
  $conn = get_db();
  $prefix = 'user_';
  $exclude = ['user_id'];

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

function email_exists(string $user_email) {
  $conn = get_db();
  $q = $conn->query("SELECT user_id FROM users WHERE user_email='$user_email' LIMIT 1");
  $res = $q->fetch_assoc();

  return $res ? $res['user_id'] : null;
}

function delete_user(int $user_id) {
  $conn = get_db();
  
  $conn->query("UPDATE users SET user_isactive=0 WHERE user_id=$user_id");
}

function fetch_all_active_users() {
  $conn = get_db();

  $res = $conn->query("SELECT
      users.user_id,
      users.user_name,
      users.user_email,
      users.user_phone,
      branches.branch_name,
      areas.area_name,
      regions.region_name 
    FROM users
    INNER JOIN branches ON branches.branch_id=users.branch_id
    INNER JOIN areas ON areas.area_id=branches.area_id
    INNER JOIN regions ON regions.region_id=areas.region_id
    WHERE users.user_isactive=1
  ");

  return $res->fetch_all(MYSQLI_ASSOC);
}

function fetch_user(int $user_id) {
  $conn = get_db();

  $res = $conn->query("SELECT
      users.user_id,
      users.user_name,
      users.user_email,
      users.user_phone,
      branches.branch_id,
      areas.area_id,
      regions.region_id 
    FROM users
    INNER JOIN branches ON branches.branch_id=users.branch_id
    INNER JOIN areas ON areas.area_id=branches.area_id
    INNER JOIN regions ON regions.region_id=areas.region_id
    WHERE users.user_id=$user_id
  ");

  return $res->fetch_assoc();
}
