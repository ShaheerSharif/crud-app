<?php
include('../includes/styles.php');
include('../lib/admin.php');

$conn = require_once '../config/db.php';

if (isset($_POST['submit'])) {
  $admin_name = $_POST['name'];
  $admin_email = $_POST['email'];
  $admin_pass = $_POST['password'];

  if ($admin_pass !== $_POST['confirm-password']) {
    die("Passwords do not match");
  }

  create_new_admin($conn, $admin_name, $admin_email, $admin_pass);

  header('Location: login.php');
  exit;
}
?>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-sm" style="width: 100%; max-width: 420px;">
    <div class="card-body">

      <h4 class="text-center mb-4">Create Account</h4>

      <form method='post'>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input 
            type="text" 
            name="name" 
            class="form-control" 
            placeholder="Enter username" 
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input 
            type="email" 
            name="email" 
            class="form-control" 
            placeholder="Enter email" 
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input 
            type="password" 
            name="password" 
            class="form-control" 
            placeholder="Create password" 
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input 
            type="password" 
            name="confirm-password" 
            class="form-control" 
            placeholder="Repeat password" 
            required
          >
        </div>
        <small id="pass-error" class="text-danger d-none">
          Passwords do not match
        </small>

        <button name="submit" type="submit" class="btn btn-success w-100">
          Sign Up
        </button>

      </form>

      <div class="text-center mt-3">
        <small>
          Already have an account? <a href="login.php">Login</a>
        </small>
      </div>

    </div>
  </div>
</div>

</body>

<?php include('../includes/scripts.php'); ?>
<script src="../js/confirm_password.js"></script>
