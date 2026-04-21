<?php
include("../config/db.php");
include("../includes/styles.php");
include("../lib/csv_parser.php");
include("../lib/location_data.php");
include("../lib/user.php");

if (isset($_POST['submit'])) {
  $filename = $_FILES["file"]["tmp_name"];
  $rows = csv_to_arr($filename);

  foreach ($rows as $r) {
    if ($res = branch_exists($conn, $r['region_name'], $r['area_name'], $r['branch_name'])) {

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $r['user_name'],
          $r['user_phone'],
          $res['branch_id']
        );
      }

      create_user($conn, $r['user_name'], $r['user_email'], $r['user_phone'], $res['branch_id']);
    }

    else if ($res = area_exists($conn, $r['region_name'], $r['area_name'])) {
      $branch_id = create_branch($conn, $r['branch_name'], $res['area_id']);

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $r['user_name'],
          $r['user_phone'],
          $branch_id
        );
      }

      create_user($conn, $r['user_name'], $r['user_email'], $r['user_phone'], $branch_id);
    }

    else if ($res = region_exists($conn, $r['region_name'])) {
      $area_id = create_area($conn, $r['area_name'], $res['region_id']);
      $branch_id = create_branch($conn, $r['branch_name'], $area_id);

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $r['user_name'],
          $r['user_phone'],
          $branch_id
        );
      }

      create_user($conn, $r['user_name'], $r['user_email'], $r['user_phone'], $branch_id);
    }

    else {
      $region_id = create_region($conn, $r['region_name']);
      $area_id = create_area($conn, $r['area_name'], $region_id);
      $branch_id = create_branch($conn, $r['branch_name'], $area_id);

      if ($user_id = email_exists($conn, $r['user_email'])) {
        update_user_all_except_email(
          $conn,
          $user_id,
          $r['user_name'],
          $r['user_phone'],
          $branch_id
        );
      }

      create_user($conn, $r['user_name'], $r['user_email'], $r['user_phone'], $branch_id);
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
