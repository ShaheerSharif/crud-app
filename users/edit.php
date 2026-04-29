<?php

$payload = require_once __DIR__ . '/../middleware/auth.php';

require_once __DIR__ . '/../lib/user.php';

include("../includes/styles.php");

$id = $_GET['user_id'];

$default_row = fetch_user($id);

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $branch_id = $_POST['branch_id'];

  update_user($id, $branch_id, ['user_name' => $name, 'user_email' => $email, 'user_phone' => $phone]);
  header("Location: /crud-app/users");
  exit;
}
?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Edit User</h4>
    </div>

    <div class="card-body">
      <form method='post'>

        <div class="mb-3">
          <label class="form-label">Name</label>
          <?php echo "<input type='text' name='name' class='form-control' value={$default_row['user_name']} required>" ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <?php echo "<input type='text' name='email' id='email' class='form-control' value={$default_row['user_email']} required>" ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone</label>
          <?php echo "<input type='text' name='phone' class='form-control' value={$default_row['user_phone']} required>" ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Region</label>
          <select name="region_id" id="region" class="form-select" required>
            <option value="">Select Region</option>
            <?php
            $regions = fetch_regions();
            foreach ($regions as $region) {
              if ($default_row['region_id'] === $region['region_id']) {
                echo "<option value='{$region['region_id']}' selected>{$region['region_name']}</option>";
              } else {
                echo "<option value='{$region['region_id']}'>{$region['region_name']}</option>";
              }
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Area</label>
          <select name="area_id" id="area" class="form-select" required>
            <option value="">Select Area</option>
            <?php
            $areas = fetch_areas($default_row['region_id']);
            foreach ($areas as $area) {
              if ($default_row['area_id'] === $area['area_id']) {
                echo "<option value='{$area['area_id']}' selected>{$area['area_name']}</option>";
              } else {
                echo "<option value='{$area['area_id']}'>{$area['area_name']}</option>";
              }
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Branch</label>
          <select name="branch_id" id="branch" class="form-select" required>
            <option value="">Select Branch</option>
            <?php
            $branches = fetch_branches($default_row['area_id']);
            foreach ($branches as $branch) {
              if ($default_row['branch_id'] === $branch['branch_id']) {
                echo "<option value='{$branch['branch_id']}' selected>{$branch['branch_name']}</option>";
              } else {
                echo "<option value='{$branch['branch_id']}'>{$branch['branch_name']}</option>";
              }
            }
            ?>
          </select>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" name="submit" class="btn btn-success">
            Save
          </button>

          <button type="button" class="btn btn-secondary" onclick="window.location.href='../'">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('../includes/scripts.php') ?>

<script src="../js/branch_dropdown.js"></script>
<script src="../js/email_verification.js"></script>
