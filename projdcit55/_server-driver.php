<?php
include '_functions.php';
if (isset($_SESSION['driver'])) {
    viewLicenseViolationsUser($_SESSION['driver']);
}
?>
