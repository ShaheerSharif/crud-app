<?php
include("../config/db.php");
include("../includes/styles.php");
include("../lib/user.php");
include('../middleware/auth.php');

require_auth();

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $branch_id = $_POST['branch_id'];

  create_user($conn, $name, $email, $phone, $branch_id);
  header("Location: ../");
  exit;
}
?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Add User</h4>
    </div>

    <div class="card-body">
      <form method='post'>

        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" id='name' class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" required>
          <div id="email_msg" class="mt-1"></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" id='phone' class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Region</label>
          <select name="region_id" id="region" class="form-select" required>
            <option value="">Select Region</option>
            <?php
            $regions = mysqli_query($conn, "SELECT * FROM regions");
            while ($row = mysqli_fetch_assoc($regions)) {
              echo "<option value='{$row['region_id']}'>{$row['region_name']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Area</label>
          <select name="area_id" id="area" class="form-select" disabled required>
            <option value="">Select Area</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Branch</label>
          <select name="branch_id" id="branch" class="form-select" disabled required>
            <option value="">Select Branch</option>
          </select>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" name="submit" class="btn btn-success" id='submit_btn' disabled>
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
