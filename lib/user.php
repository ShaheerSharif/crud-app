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
