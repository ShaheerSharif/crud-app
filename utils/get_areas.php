<?php
require_once __DIR__ . '/../lib/location_data.php';

$region_id = $_POST['region_id'];

$areas = fetch_areas($region_id);

echo '<option value="">Select Area</option>';

foreach ($areas as $area) {
  echo "<option value='{$area['area_id']}'>{$area['area_name']}</option>";
}
