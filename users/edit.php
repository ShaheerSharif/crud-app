<?php
include("../config/db.php");
include("../includes/styles.php");

$id = $_GET['user_id'];

$result = mysqli_query($conn, "
  SELECT
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
  WHERE users.user_id=$id
");

$default_row = $result->fetch_assoc();

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $branch_id = $_POST['branch_id'];

  mysqli_query($conn, "
    INSERT INTO users (user_name, user_email, user_phone, branch_id)
    VALUES ('$name', '$email', '$phone', '$branch_id')
  ");
  header("Location: ../");
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
            $regions = mysqli_query($conn, "SELECT * FROM regions");
            while ($row = mysqli_fetch_assoc($regions)) {
              if ($default_row['region_id'] === $row['region_id']) {
                echo "<option value='{$row['region_id']}' selected>{$row['region_name']}</option>";
              } else {
                echo "<option value='{$row['region_id']}'>{$row['region_name']}</option>";
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
            $areas = mysqli_query($conn, "SELECT * FROM areas WHERE region_id={$default_row['region_id']}");
            while ($row = mysqli_fetch_assoc($areas)) {
              if ($default_row['area_id'] === $row['area_id']) {
                echo "<option value='{$row['area_id']}' selected>{$row['area_name']}</option>";
              } else {
                echo "<option value='{$row['area_id']}'>{$row['area_name']}</option>";
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
            $branches = mysqli_query($conn, "
              SELECT branches.branch_id, branches.branch_name
              FROM branches
              INNER JOIN areas ON
                areas.area_id=branches.area_id
              WHERE
                area.region_id={$default_row['region_id']} AND
                branches.area_id={$default_row['area_id']}
            ");
            while ($row = mysqli_fetch_assoc($branches)) {
              if ($default_row['branch_id'] === $row['branch_id']) {
                echo "<option value='{$row['branch_id']}' selected>{$row['branch_name']}</option>";
              } else {
                echo "<option value='{$row['branch_id']}'>{$row['branch_name']}</option>";
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
