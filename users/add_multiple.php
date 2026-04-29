<?php

$payload = require_once __DIR__ . '/../middleware/auth.php';

require_once __DIR__ . '/../lib/column_helpers.php';
require_once __DIR__ . '/../lib/csv_helpers.php';
require_once __DIR__ . '/../lib/location_data.php';
require_once __DIR__ . '/../lib/logging.php';
require_once __DIR__ . '/../lib/user.php';

include("../includes/styles.php");

if (isset($_POST['submit'])) {
  $filename = $_FILES["file"]["tmp_name"];
  $data = csv_to_arr($filename);
  $rows = $data['rows'];
  $colNames = $data['headers'];

  $needles = ['user_name', 'user_email', 'branch_name', 'area_name', 'region_name'];

  // check if csv file has mandatory columns
  if (!empty(array_diff($needles, $colNames))) {
    echo "<script>alert('Missing relevant columns.');</script>";
    exit;
  }

  foreach ($colNames as $c) {
    $arr = identify_relevant_table($c);
    $table = $arr['table'];

    if ($table) {
      if (!$arr['exists']) {
        add_column($table, $c);
      }
    }

    // if relevant table does not exist then remove from rows
    else {
      sql_log('add_multiple', "Column name ($c) couldn't be mapped to any table");

      foreach ($rows as &$r) {
        unset($r[$c]);
      }
    }
  }

  foreach ($rows as $row) {
    $branch_id = null;
    $area_id = null;
    $region_id = null;

    // create user
    if ($res = branch_exists($row['region_name'], $row['area_name'], $row['branch_name'])) {
      $region_id = $res['region_id'];
      $area_id = $res['area_id'];
      $branch_id = $res['branch_id'];

      if ($user_id = email_exists($row['user_email'])) {
        update_user_all_except_email(
          $user_id,
          $branch_id,
          $row
        );
      }

      else {
        create_user($row['user_email'], $branch_id, $row);
      }
    }

    // create user and branch
    else if ($res = area_exists($row['region_name'], $row['area_name'])) {
      $region_id = $res['region_id'];
      $area_id = $res['area_id'];
      $branch_id = create_branch($row['branch_name'], $res['area_id'], $row);

      if ($user_id = email_exists($row['user_email'])) {
        update_user_all_except_email(
          $user_id,
          $branch_id,
          $row
        );
      }

      else {
        create_user($row['user_email'], $branch_id, $row);
      }
    }

    // create user, branch and area
    else if ($res = region_exists($row['region_name'])) {
      $region_id = $res['region_id'];
      $area_id = create_area($row['area_name'], $res['region_id'], $row);
      $branch_id = create_branch($row['branch_name'], $area_id, $row);

      if ($user_id = email_exists($row['user_email'])) {
        update_user_all_except_email(
          $user_id,
          $branch_id,
          $row
        );
      }

      else {
        create_user($row['user_email'], $branch_id, $row);
      }
    }

    // create user, branch, area and region
    else {
      $region_id = create_region($row['region_name'], $row);
      $area_id = create_area($row['area_name'], $region_id, $row);
      $branch_id = create_branch($row['branch_name'], $area_id, $row);

      if ($user_id = email_exists($row['user_email'])) {
        update_user_all_except_email(
          $user_id,
          $branch_id,
          $row
        );
      }

      else {
        create_user($row['user_email'], $branch_id, $row);
      }
    }

    if ($branch_id) update_optional_branch_fields($branch_id, $row);
    if ($area_id) update_optional_area_fields($area_id, $row);
    if ($region_id) update_optional_region_fields($region_id, $row);
  }

  header("Location: ./");
  exit;
}
?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Add Multiple Users</h4>
    </div>

    <div class="card-body">
      <form method='post' enctype="multipart/form-data">
        <div class="mb-4">
          <label class="form-label fw-semibold">Select CSV File</label>
          <input 
            type="file"
            name="file"
            accept=".csv"
            class="form-control form-control-lg"
            required
          >
          <div class="form-text">Only .csv files are allowed</div>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" name="submit" class="btn btn-success btn-lg">
            Upload
          </button>
          <button
            type="button" 
            class="btn btn-outline-secondary"
            onclick="window.location.href='../'">
            Cancel
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
