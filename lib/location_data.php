<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Checks if branch exists in a region and area. Returns region, area and branch id if exists
 */
function branch_exists(string $region_name, string $area_name, string $branch_name) {
  $conn = get_db();
  $q = $conn->query("SELECT
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
    LIMIT 1
  ");

  $res = $q->fetch_assoc();

  return $res;
}

/**
 * Checks if area exists in a region. Returns region and area id if exists.
 */
function area_exists(string $region_name, string $area_name) {
  $conn = get_db();
  $q = $conn->query("SELECT
      regions.region_id,
      areas.area_id
    FROM regions
    INNER JOIN areas ON areas.region_id=regions.region_id
    WHERE
      regions.region_name LIKE '$region_name'
    AND
      areas.area_name LIKE '$area_name'
    LIMIT 1
  ");

  $res = $q->fetch_assoc();

  return $res;
}

/**
 * Checks if region exists. Returns region id if exists.
 */
function region_exists(string $region_name) {
  $conn = get_db();
  $q = $conn->query("SELECT region_id FROM regions WHERE region_name LIKE '$region_name' LIMIT 1");
  $res = $q->fetch_assoc();

  return $res;
}

/**
 * Creates new branch, returns branch id.
 */
function create_branch(string $branch_name, int $area_id, array $fields) {
  $conn = get_db();
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
    $conn->query("INSERT INTO branches(branch_name, area_id) VALUES ('$branch_name', $area_id)");
  } else {
    $conn->query("INSERT INTO branches(branch_name, area_id, $sqlCols) VALUES ('$branch_name', $area_id, $sqlVals)");
  }

  $branch_id = mysqli_insert_id($conn);
  return $branch_id;
}

/**
 * Creates new area, returns area id.
 */
function create_area(string $area_name, int $region_id, array $fields) {
  $conn = get_db();
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
    $conn->query("INSERT INTO areas(area_name, region_id) VALUES ('$area_name', $region_id)");
  } else {
    $conn->query("INSERT INTO areas(area_name, region_id, $sqlCols) VALUES ('$area_name', $region_id, $sqlVals)");
  }

  $area_id = mysqli_insert_id($conn);
  return $area_id;
}

/**
 * Creates new region, returns region id.
 */
function create_region(string $region_name, array $fields) {
  $conn = get_db();
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
    $conn->query("INSERT INTO regions(region_name) VALUES ('$region_name')");
  } else {
    $conn->query("INSERT INTO regions(region_name, $sqlCols) VALUES ('$region_name', $sqlVals)");
  }

  $region_id = mysqli_insert_id($conn);
  return $region_id;
}

function update_optional_branch_fields(int $branch_id, array $fields) {
  $conn = get_db();
  $prefix = 'branch_';
  $exclude = ['branch_id', 'branch_name'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlParts = [];

  foreach ($fields as $key => $val) {
    $sqlParts[] = "`$key`='$val'";
  }

  $sql = implode(', ', $sqlParts);

  if ($sql !== '') {
    $conn->query("UPDATE branches SET $sql WHERE branch_id=$branch_id");
  }
}

function update_optional_area_fields(int $area_id, array $fields) {
  $conn = get_db();
  $prefix = 'area_';
  $exclude = ['area_id', 'area_name'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlParts = [];

  foreach ($fields as $key => $val) {
    $sqlParts[] = "`$key`='$val'";
  }

  $sql = implode(', ', $sqlParts);

  if ($sql !== '') {
    $conn->query("UPDATE areas SET $sql WHERE area_id=$area_id");
  }
}

function update_optional_region_fields(int $region_id, array $fields) {
  $conn = get_db();
  $prefix = 'region_';
  $exclude = ['region_id', 'region_name'];

  $fields = filter_keys_starting_with($fields, $prefix);
  $fields = filter_all_keys_except($fields, $exclude);

  $sqlParts = [];

  foreach ($fields as $key => $val) {
    $sqlParts[] = "`$key`='$val'";
  }

  $sql = implode(', ', $sqlParts);

  if ($sql !== '') {
    $conn->query("UPDATE regions SET $sql WHERE region_id=$region_id");
  }
}

function fetch_branches(?int $area_id) {
  $conn = get_db();
  $res = null;

  if ($area_id === null) {
    $res = $conn->query("SELECT branch_id, branch_name FROM branch");
  } else {
    $res = $conn->query("SELECT branch_id, branch_name FROM branch WHERE area_id=$area_id");
  }
  return $res->fetch_all(MYSQLI_ASSOC);
}

function fetch_areas(?int $region_id) {
  $conn = get_db();
  $res = null;

  if ($region_id === null) {
    $res = $conn->query("SELECT area_id, area_name FROM areas");
  } else {
    $res = $conn->query("SELECT area_id, area_name FROM areas WHERE region_id=$region_id");
  }
  return $res->fetch_all(MYSQLI_ASSOC);
}

function fetch_regions() {
  $conn = get_db();
  $res = $conn->query("SELECT region_id, region_name FROM regions");
  return $res->fetch_all(MYSQLI_ASSOC);
}
