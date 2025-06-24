<?php
include '_functions.php';

if (isset($_SESSION['driver'])) {
    $info = getLicenseInfoUser($_SESSION['driver']);
    $userid = $info[0]['userId'];
    $drivername = $info[0]['name'];
    $sex = $info[0]['sex'];
    $driveraddress = $info[0]['address'];
    $licensenum = $info[0]['licenseNumber'];
    $dateregistered = $info[0]['dateRegistered'];
    $daterenewed = $info[0]['dateRenewed'] == null ? 'No Record' : $info[0]['dateRenewed'];
    $expirationdate = $info[0]['expirationDate'];
    $status = $info[0]['status'];

    echo "
        <h3 class='mb-3'>ID: [" . $userid . "]</h3>
        <h3 class='mb-3'><i class='bi bi-person-circle me-2'></i>Driver Information</h3>
        <div class='driver-info-fields'>
            <div><i class='bi bi-person me-2'></i><b>Name:</b><br>" . $drivername . "</div>
            <div><i class='bi bi-gender-ambiguous me-2'></i><b>Sex:</b> " . $sex . "</div>
            <div><i class='bi bi-credit-card-2-front me-2'></i><b>License Number:</b><br>" . $licensenum . "</div>
            <div><i class='bi bi-info-circle me-2'></i><b>Status:</b> <span class='status-" . $status . "'>" . $status . "</span></div>
            <div><i class='bi bi-calendar-plus me-2'></i><b>Date Registered:</b><br>" . date_format(date_create($dateregistered),"F j, Y") . "</div>
            <div><i class='bi bi-arrow-repeat me-2'></i><b>Date Renewed:</b><br>" . ($daterenewed == 'No Record' ? 'No Record' : date_format(date_create($daterenewed),"F j, Y")) . "</div>
            <div><i class='bi bi-calendar-x me-2'></i><b>Expiration Date:</b><br>" . date_format(date_create($expirationdate),"F j, Y") . "</div>
            <div><i class='bi bi-geo-alt me-2'></i><b>Address:</b><br>" . $driveraddress . "</div>
        </div>
    ";
}
?>