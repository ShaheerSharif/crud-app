<?php
include("../config/db.php");

$area_id = $_POST['area_id'];

$query = mysqli_query($conn, "SELECT * FROM branches WHERE area_id='$area_id'");

echo '<option value="">Select Branch</option>';

while ($row = mysqli_fetch_assoc($query)) {
  echo "<option value='{$row['branch_id']}'>{$row['branch_name']}</option>";
}
