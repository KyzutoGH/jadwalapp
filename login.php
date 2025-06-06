<?php
session_start();
$judul_browser = 'Fukubi Admin - Login';
require_once('bagian/Header.php');

// Redirect to 404 if accessing forgot-password.php or register.php directly
if (basename($_SERVER['PHP_SELF']) === 'forgot-password.php' || basename($_SERVER['PHP_SELF']) === 'register.php') {
  header("Location: bagian/header/404.php");
  exit;
}
?>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>Fukubi</b> Store</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Login Dulu Bosku</p>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']); // Clear the error after displaying
            ?>
          </div>
        <?php endif; ?>

        <form action="config/proses_login.php" method="post">
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>
        <!-- <p class="mb-1">
          <a href="#" onclick="redirectTo404()">I forgot my password</a> -->
        <!-- </p>
        <p class="mb-0">
          <a href="#" onclick="redirectTo404()" class="text-center">Register a new membership</a>
        </p>
      </div> -->
      </div>
    </div>

    <script>
      function redirectTo404() {
        sessionStorage.setItem('redirected', 'true');
        window.location.href = 'bagian/header/404.php';
      }

      if (sessionStorage.getItem('redirected')) {
        sessionStorage.removeItem('redirected');
        window.location.href = 'login.php';
      }
    </script>

    <?php require_once('bagian/Footer.php'); ?>