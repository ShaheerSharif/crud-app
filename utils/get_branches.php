<?php
require_once __DIR__ . '/../lib/location_data.php';

$area_id = $_POST['area_id'];

$branches = fetch_branches($area_id);

echo '<option value="">Select Branch</option>';

foreach ($branches as $branch) {
  echo "<option value='{$branch['branch_id']}'>{$branch['branch_name']}</option>";
}
