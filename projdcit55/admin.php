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
        <h1>Admin</h1>
        <form method="post" action="_functions.php">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" class="form-control" required>
            <label for="password" class="form-label">Password</label>
            <input type="text" id="password" class="form-control" required>
            <button type="submit" name="admin-login" class="btn btn-primary">Login</button>
        </form>
        <a href="index.php">Home</a>
    </div>

</body>
</html>