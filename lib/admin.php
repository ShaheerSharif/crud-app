<?php

function create_new_admin($conn, string $admin_name, string $admin_email, string $admin_pass) {
  $hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);

  mysqli_query($conn, "INSERT INTO admins (admin_name, admin_email, admin_pass) VALUES ('$admin_name', '$admin_email', '$hashed_pass')");

  $admin_id = mysqli_insert_id($conn);

  return $admin_id;
}

function verify_admin($conn, string $admin_email, string $admin_pass) {
  $q = mysqli_query($conn, "
    SELECT
      admin_id,
      admin_email,
      admin_pass
    FROM admins
    WHERE admin_email='$admin_email'
    LIMIT 1
  ");

  if ($q->num_rows === 0) return false;

  $admin = $q->fetch_assoc();

  return [
    'admin_id' => $admin['admin_id'],
    'status' => password_verify($admin_pass, $admin['admin_pass'])
  ];
}
