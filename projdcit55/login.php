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

    <div class="container">
        <h1>User</h1>
        <form method="post" action="_functions.php">

            <label for="licenseNumber" class="form-label">License Number</label>
            <input type="text" id="licenseNumber" class="form-control" name="license-number" required>
            
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-control" name="password" required>

            <button type="submit" class="btn btn-success" name="driver-login">Login</button>
        </form>
        <a href="index.php">Home</a>
    </div>

</body>
</html>