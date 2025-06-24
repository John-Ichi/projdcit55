<?php
include '_functions.php';

if (isset($_SESSION['driver']) && $_SESSION['driver'] != '') {
    header('Location: user-db.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProjectLisensya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/admin-custom.css">
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg custom-header">
          <div class="container-fluid header-flex">
            <a class="navbar-brand logo-brand" href="#">
              <span class="logo-icon"><i class="bi bi-shield-check"></i></span>
              <span class="logo-text">Project<span class="logo-accent">Lisensya</span></span>
            </a>
            <div class="header-center ms-auto" style="justify-content: flex-end;">
              <ul class="navbar-nav flex-row gap-3 me-5">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-box-arrow-in-right"></i>Login</a></li>
                <li class="nav-item"><a class="nav-link" href="admin.php"><i class="bi bi-person-gear"></i>Admin</a></li>
              </ul>
            </div>
          </div>
        </nav>
    </header>

    <div class="container login-container d-flex flex-column justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-12 d-flex flex-column align-items-center">
                <div class="login-card-split animate-login-card">
                    <div class="login-card-left">
                        <i class="bi bi-shield-lock-fill"></i>
                        <h2>Welcome Back!</h2>
                        <p>Sign in to access your ProjectLisensya account and view your license information.</p>
                    </div>
                    <div class="login-card-right">
                        <h3 class="mb-4 text-primary text-center"><i class="bi bi-person-circle"></i> Login</h3>
                        <form method="post" action="_functions.php" class="w-100 animate-login-form" autocomplete="off">
                            <div class="mb-3">
                                <label for="licenseNumber" class="form-label"><i class="bi bi-credit-card-2-front form-icon"></i> License Number</label>
                                <input type="text" id="licenseNumber" class="form-control" name="license-number" minlength="8" maxlength="8" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="bi bi-lock-fill form-icon"></i> Password</label>
                                <input type="password" id="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 animate-login-btn" name="driver-login"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>