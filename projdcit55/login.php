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
</head>
<body>

    <header>

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">ProjectLisensya</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header>

    <div class="container">
        <div class="row">
            <div class="col d-flex justify-content-center align-items-center">
                <h3>User Login</h3>
            </div>
            <div class="col-7">

                <form method="post" action="_functions.php">

                    <label for="licenseNumber" class="form-label">License Number</label>
                    <input type="text" id="licenseNumber" class="form-control" name="license-number" minlength="8" maxlength="8" required>
                    
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" name="password" required>

                    <button type="submit" class="btn btn-primary" name="driver-login" style="margin-top: 0.5rem;">Login</button>
                </form>

            </div>
        </div>
    </div>

</body>
</html>