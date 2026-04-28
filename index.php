<?php
include("config/db.php");
include("includes/styles.php");
include('middleware/auth.php');

require_auth();

$adminName = 'example';

$result = mysqli_query($conn, "
  SELECT
    users.user_id,
    users.user_name,
    users.user_email,
    users.user_phone,
    branches.branch_name,
    areas.area_name,
    regions.region_name 
  FROM users
  INNER JOIN branches ON branches.branch_id=users.branch_id
  INNER JOIN areas ON areas.area_id=branches.area_id
  INNER JOIN regions ON regions.region_id=areas.region_id
  WHERE users.user_isactive=1
");
?>

<link href="./styles/index.css" rel="stylesheet">

<nav class="navbar navbar-dark bg-dark shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">

    <span class="navbar-brand fw-semibold mb-0">
      Dashboard <span class="text-muted small">| Admin Panel</span>
    </span>

    <span class="text-light me-3 d-none d-md-inline">
      Hi, <strong><?php echo htmlspecialchars($adminName); ?></strong>
    </span>

    <a href="auth/logout.php" class="btn btn-danger btn-sm">
      Logout
    </a>

  </div>
</nav>

<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Users</h2>
    <div>
      <a href="users/add.php" class="btn btn-primary btn-sm">
        Create New User
      </a>
      <a href="users/add_multiple.php" class="btn btn-primary btn-sm">
        Add Multiple Users
      </a>
    </div>
  </div>

  <div class="table-responsive table-scroll">
    <table class="table table-striped table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Branch</th>
          <th>Area</th>
          <th>Region</th>
          <th style="width: 140px;">Action</th>
        </tr>
      </thead>

      <tbody>
        <?php
        if ($result->num_rows !== 0) {
          while($row = $result->fetch_assoc()) {
        ?>
          <tr>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= htmlspecialchars($row['user_phone']) ?></td>
            <td><?= htmlspecialchars($row['branch_name']) ?></td>
            <td><?= htmlspecialchars($row['area_name']) ?></td>
            <td><?= htmlspecialchars($row['region_name']) ?></td>
            <td>
              <a href="users/edit.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-warning">
                Edit
              </a>

              <a href="users/delete.php?user_id=<?= $row['user_id'] ?>"
                class="btn btn-sm btn-danger"
                onclick="return confirm('Delete?')">
                Delete
              </a>
            </td>
          </tr>
          <?php } ?>
        <?php }
          else { ?>
            <tr >
              <td colspan="7" class="text-center py-4 text-muted fst-italic">No users found</td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
