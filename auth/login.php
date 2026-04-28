<?php
include('../config/db.php');
include('../includes/styles.php');
include('../lib/admin.php');
include('../lib/jwt.php');

if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $remember = isset($_POST['remember']);

  $admin = verify_admin($conn, $email, $pass);

  if ($admin['status']) {
    $ttl = 3600; // 1 hr

    if ($remember) {
      $ttl = 3600 * 24 * 30; // 30 days
    }

    $jwt = gen_jwt($admin['admin_id'], $ttl);
    setcookie('token', $jwt, [
      'expires' => time() + $ttl - 180, // cookie expires 3 mins before token
      'path' => '/',
      'httponly' => true,
      'secure' => true,
      'samesite' => 'Strict',
    ]);

    header('Location: ../');
    exit;
  } else {
    echo "<script>alert('Login Failed.');</script>";
  }
}
?>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
    <div class="card-body">

      <h4 class="text-center mb-4">Login</h4>

      <form method='post'>
        
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
            placeholder="Enter password" 
            required
          >
        </div>

        <div class="form-check mb-3">
          <input 
            type="checkbox" 
            name="remember" 
            class="form-check-input" 
            id="remember"
          >
          <label class="form-check-label" for="remember">
            Remember me
          </label>
        </div>

        <button name="submit" type="submit" class="btn btn-primary w-100">
          Login
        </button>

      </form>

      <!-- <div class="text-center mt-3">
        <a href="#" class="btn btn-link">Forgot password?</a>
      </div> -->
      
      <div class="text-center mt-3">
        <small>
          Don't have and account? <a href="signup.php">Sign Up</a>
        </small>
      </div>
    </div>
  </div>
</div>

</body>
