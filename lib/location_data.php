<?php

/**
 * Checks if branch exists in a region and area. Returns region, area and branch id if exists
 */
function branch_exists($conn, $region_name, $area_name, $branch_name) {
  $q = mysqli_query($conn, "
    SELECT
      regions.region_id,
      areas.area_id,
      branches.branch_id
    FROM regions
    INNER JOIN areas ON areas.region_id=regions.region_id
    INNER JOIN branches ON branches.area_id=areas.area_id
    WHERE
      regions.region_name LIKE '$region_name'
    AND
      areas.area_name LIKE '$area_name'
    AND
      branches.branch_name LIKE '$branch_name' 
  ");

  $res = $q->fetch_assoc();

  return $res;
}

/**
 * Checks if area exists in a region. Returns region and area id if exists.
 */
function area_exists($conn, $region_name, $area_name) {
  $q = mysqli_query($conn, "
    SELECT
      regions.region_id,
      areas.area_id
    FROM regions
    INNER JOIN areas ON areas.region_id=regions.region_id
    WHERE
      regions.region_name LIKE '$region_name'
    AND
      areas.area_name LIKE '$area_name'
  ");

  $res = $q->fetch_assoc();

  return $res;
}

/**
 * Checks if region exists. Returns region id if exists.
 */
function region_exists($conn, $region_name) {
  $q = mysqli_query($conn, "SELECT region_id FROM regions WHERE region_name LIKE '$region_name'");
  $res = $q->fetch_assoc();

  return $res;
}

/**
 * Creates new branch, returns branch id.
 */
function create_branch($conn, $branch_name, $area_id, $fields) {
  $prefix = 'branch_';
  $exclude = ['branch_id', 'branch_name'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlColParts = [];
  $sqlValParts = [];

  foreach ($fields as $key => $val) {
    $sqlColParts[] = "`$key`";
    $sqlValParts[] = "'$val'";
  }

  $sqlCols = implode(', ', $sqlColParts);
  $sqlVals = implode(', ', $sqlValParts);

  if ($sqlCols === '' || $sqlVals === '') {
    mysqli_query($conn, "INSERT INTO branches(branch_name, area_id) VALUES ('$branch_name', $area_id)");
  } else {
    mysqli_query($conn, "INSERT INTO branches(branch_name, area_id, $sqlCols) VALUES ('$branch_name', $area_id, $sqlVals)");
  }

  $branch_id = mysqli_insert_id($conn);
  return $branch_id;
}

/**
 * Creates new area, returns area id.
 */
function create_area($conn, $area_name, $region_id, $fields) {
  $prefix = 'area_';
  $exclude = ['area_id', 'area_name'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlColParts = [];
  $sqlValParts = [];

  foreach ($fields as $key => $val) {
    $sqlColParts[] = "`$key`";
    $sqlValParts[] = "'$val'";
  }

  $sqlCols = implode(', ', $sqlColParts);
  $sqlVals = implode(', ', $sqlValParts);

  if ($sqlCols === '' || $sqlVals === '') {
    mysqli_query($conn, "INSERT INTO areas(area_name, region_id) VALUES ('$area_name', $region_id)");
  } else {
    mysqli_query($conn, "INSERT INTO areas(area_name, region_id, $sqlCols) VALUES ('$area_name', $region_id, $sqlVals)");
  }

  $area_id = mysqli_insert_id($conn);
  return $area_id;
}

/**
 * Creates new region, returns region id.
 */
function create_region($conn, $region_name, $fields) {
  $prefix = 'region_';
  $exclude = ['region_id', 'region_name'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlColParts = [];
  $sqlValParts = [];

  foreach ($fields as $key => $val) {
    $sqlColParts[] = "`$key`";
    $sqlValParts[] = "'$val'";
  }

  $sqlCols = implode(', ', $sqlColParts);
  $sqlVals = implode(', ', $sqlValParts);

  if ($sqlCols === '' || $sqlVals === '') {
    mysqli_query($conn, "INSERT INTO regions(region_name) VALUES ('$region_name')");
  } else {
    mysqli_query($conn, "INSERT INTO regions(region_name, $sqlCols) VALUES ('$region_name', $sqlVals)");
  }

  $region_id = mysqli_insert_id($conn);
  return $region_id;
}
