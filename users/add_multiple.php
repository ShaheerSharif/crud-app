<?php
include("../config/db.php");
include("../config/aliases.php");
include("../includes/styles.php");
include("../lib/column_helpers.php");
include("../lib/csv_helpers.php");
include("../lib/location_data.php");
include("../lib/logging.php");
include("../lib/user.php");

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
    $arr = identify_relevant_table($conn, $c, $aliasTable);
    $table = $arr['table'];

    if ($table) {
      if (!$arr['exists']) {
        add_column($conn, $table, $c);
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

  foreach ($rows as $r) {
    if ($res = branch_exists($conn, $r['region_name'], $r['area_name'], $r['branch_name'])) {

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $res['branch_id'],
          $r
        );
      }

      else {
        create_user($conn, $r['user_email'], $res['branch_id'], $r);
      }
    }

    else if ($res = area_exists($conn, $r['region_name'], $r['area_name'])) {
      $branch_id = create_branch($conn, $r['branch_name'], $res['area_id'], $r);

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $branch_id,
          $r
        );
      }

      else {
        create_user($conn, $r['user_email'], $branch_id, $r);
      }
    }

    else if ($res = region_exists($conn, $r['region_name'])) {
      $area_id = create_area($conn, $r['area_name'], $res['region_id'], $r);
      $branch_id = create_branch($conn, $r['branch_name'], $area_id, $r);

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $branch_id,
          $r
        );
      }

      else {
        create_user($conn, $r['user_email'], $branch_id, $r);
      }
    }

    else {
      $region_id = create_region($conn, $r['region_name'], $r);
      $area_id = create_area($conn, $r['area_name'], $region_id, $r);
      $branch_id = create_branch($conn, $r['branch_name'], $area_id, $r);

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $branch_id,
          $r
        );
      }

      else {
        create_user($conn, $r['user_email'], $branch_id, $r);
      }
    }
  }

  header("Location: ../");
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
