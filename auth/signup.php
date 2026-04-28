<?php
include('../includes/styles.php');
?>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-sm" style="width: 100%; max-width: 420px;">
    <div class="card-body">

      <h4 class="text-center mb-4">Create Account</h4>

      <form method="POST" action="signup.php">

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input 
            type="text" 
            name="username" 
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
            name="confirm_password" 
            class="form-control" 
            placeholder="Repeat password" 
            required
          >
        </div>

        <button type="submit" class="btn btn-success w-100">
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
