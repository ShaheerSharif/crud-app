<?php
include("../config/db.php");

$region_id = $_POST['region_id'];

$query = mysqli_query($conn, "SELECT * FROM areas WHERE region_id='$region_id'");

echo '<option value="">Select Area</option>';

while ($row = mysqli_fetch_assoc($query)) {
  echo "<option value='{$row['area_id']}'>{$row['area_name']}</option>";
}
