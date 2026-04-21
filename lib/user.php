<?php

function create_user($conn, $user_name, $user_email, $user_phone, $branch_id) {
  mysqli_query($conn, "
    INSERT INTO users(user_name, user_email, user_phone, branch_id)
    VALUES (
      '$user_name',
      '$user_email',
      '$user_phone',
      $branch_id
    )
  ");
  
  $user_id = mysqli_insert_id($conn);

  return $user_id;
}

function update_user_all_except_email($conn, $user_id, $user_name, $user_phone, $branch_id) {
  mysqli_query($conn, "UPDATE users SET user_name=$user_name, user_phone=$user_phone, branch_id=$branch_id WHERE user_id=$user_id");
}

function email_exists($conn, $user_email) {
  $q = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email='$user_email'");
  $user_id = $q->fetch_assoc();

  return $user_id;
}
