<?php
include '_functions.php';
$conn = connect();
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
        <h1>Register</h1>
        <form method="post" action="_functions.php">
            
            <label for="licenseNumber" class="form-label">License Number</label>
            <input type="text" id="licenseNumber" class="form-control" name="license-number" minlength="8" maxlength="8" required>
            
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-control" name="password" minlength="8" required>
            
            <button type="submit" class="btn btn-primary" name="register-driver">Register</button>
        </form>
        <a href="admin.php">Admin</a>
        <a href="login.php">Login</a>
    </div>
    
</body>
</html>

<!--

Pseudocode:
1. Register to portal using license number
2. Enter license number
3. Enter a password
4. Check if license number exists
5. Register successful

-->