<?php
include '_functions.php';

if (isset($_SESSION['driver'])) {
    $deleted = checkDeleted($_SESSION['driver']);
    if ($deleted == true) {
        echo "deleted";
        unset($_SESSION['driver']);
    }
}
?>