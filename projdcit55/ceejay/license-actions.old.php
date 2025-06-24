<?php
include '_functions.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
}

if (!isset($_GET['serial-number'])) {
    header('Location: admin-db.php');
} else {
    if ($_GET['serial-number'] == '') {
        header('Location: admin-db.php');
    } else {
        $check = checkRegisteredLicense($_GET['serial-number']);
        if ($check !=  true)  {
            header('Location: admin-db.php');
        } else {
            $driverinfo = getLicenseInfo($_GET['serial-number']);
            $serialnum = $driverinfo[0]['serialNumber'];
            $drivername = $driverinfo[0]['name'];
            $sex = $driverinfo[0]['sex'];
            $driveraddress = $driverinfo[0]['address'];
            $licensenum = $driverinfo[0]['licenseNumber'];
            $dateregistered = $driverinfo[0]['dateRegistered'];
            $daterenewed = $driverinfo[0]['dateRenewed'] == null ? 'no record' : $driverinfo[0]['dateRenewed'];
            $expirationdate = $driverinfo[0]['expirationDate'];
            $status = $driverinfo[0]['status'];
        }
    }
}
checkSuspensionRevocationDeadlines();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProjectLisensya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
    </style>
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
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Admin</a>
                        </li>
                    </ul>
                </div>
                <a href="admin-db.php"><button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">Return</button></a>
            </div>
        </nav>

    </header>

    <div class="container">

        <div class="row">

            <div class="col">

                <div class="row">
                    <div class="col">
                        <p><b>Name:</b> <?php echo $drivername; echo " [" . $serialnum . "]"?></p>
                    </div>
                    <div class="col">
                        <p><b>License Number:</b> <?php echo $licensenum?></p>
                    </div>
                </div>

                <div class="row">
                    <p><b>Status:</b> <?php echo $status?></p>
                </div>

                <div class="row">
                    <div class="col">
                        <p><b>Sex:</b> <?php echo $sex?></p>
                    </div>
                    <div class="col">
                        <p><b>Address:</b> <?php echo $driveraddress?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <p><b>Date Registered:</b> <?php echo $dateregistered?></p>
                    </div>
                    <div class="col">
                        <p><b>Date Renewed:</b> <?php echo $daterenewed?></p>
                    </div>
                    <div class="col">
                        <p><b>Expiration Date:</b> <?php echo $expirationdate?></p>
                    </div>
                </div>
 
                <div class="row">

                    <div class="action-grid">
                    <?php
                    if ($status == 'Revoked') {
                        echo "
                            <div class='col'>
                                <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#unrevokeModal'>Unrevoke</button>
                            </div>
                        ";
                    } else {
                        if ($status == 'Suspended') {
                            echo "
                                <div class='col'>
                                    <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#unsuspendModal'>Unsuspend</button>
                                </div>
                            ";
                        } else {
                            echo "
                                <div class='col'>
                                    <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#renewModal'>Renew</button>
                                </div>
                                <div class='col'>
                                    <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#suspendModal'>Suspend</button>
                                </div>
                            ";
                        }
                        echo "
                            <div class='col'>
                                <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#revokeModal'>Revoke</button>
                            </div>
                        ";
                    }
                    ?>

                    <div class="col">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileViolationModal">File Violation</button>
                    </div>

                    <div class="col">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editInformationModal">Edit Information</button>
                    </div>

                    <div class="col">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteLicenseRecordModal">Delete Record</button>
                    </div>
                    </div>



                </div>

            </div>

            <div class="col-7">

                <table class="table">

                    <tr>
                        <th>ID</th>
                        <th>Violation</th>
                        <th>Penalty</th>
                        <th>Deadline for Settlement</th>
                        <th>Resolved</th>
                        <th></th>
                        <th></th>
                    </tr>

                    <?php viewLicenseViolations($serialnum)?>
                </table>

            </div>

        </div>

    </div>

    <div class="modal" tabindex="-1" id="renewModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">License Renewal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Extend validity by 5 years for license with serial number <?php echo $serialnum; ?>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="_functions.php">
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">
                        <input type="text" name="date-of-expiry" value="<?php echo $expirationdate?>" style="display: none;">
                        <button type="submit" class="btn btn-primary" name="renewLicense" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="suspendModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">License Suspension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Suspend license with serial number <?php echo $serialnum?>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="_functions.php">
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">
                        <button type="submit" class="btn btn-primary" name="suspendLicense" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="unsuspendModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Suspend License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Unsuspend license with serial number <?php echo $serialnum?>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="_functions.php">
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">
                        <button type="submit" class="btn btn-primary" name="unsuspendLicense" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="revokeModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Suspend License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Revoke license with serial number <?php echo $serialnum?>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="_functions.php">
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">
                        <button type="submit" class="btn btn-primary" name="revokeLicense" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="unrevokeModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Suspend License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Unrevoke license with serial number <?php echo $serialnum?>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="_functions.php">
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">
                        <button type="submit" class="btn btn-primary" name="unrevokeLicense" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="fileViolationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Suspend License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>File violation for license with serial number <?php echo $serialnum?>?</p>
                    
                    <form method="post" action="_functions.php">
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">

                        <label for="violationSelect" class="form-label">Violation</label>
                        <select class="form-select" id="violationSelect" name="violation-committed" required>
                            <option value="" style="display: none;" selected disabled>select violation committed</option>
                            <option value="Moving Violation">Moving Violation (e.g. speeding)</option>
                            <option value="Non-moving Violation">Non-Moving Violation (e.g. illegal parking)</option>
                        </select>

                        <label for="penaltySelect" class="form-label">Penalty</label>
                        <select class="form-select" id="penaltySelect" name="alloted-penalty" required>
                            <option value="" style="display: none;" selected disabled>alloted penalty</option>
                            <option value="Monetary Fine">Monetary Fine</option>
                            <option value="License Suspension">License Suspension</option>
                            <option value="License Revocation">License Revocation</option>
                        </select>

                        <label for="violationDeadline" class="form-label">Deadline for settlement</label>
                        <input type="date" id="violationDeadline" class="form-control" name="settlement-deadline" required>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">
                        <input type="text" name="license-number" value="<?php echo $licensenum?>" style="display: none;">
                        <button type="submit" class="btn btn-primary" name="fileViolation" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="editInformationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="_functions.php">

                    <label for="driverName" class="form-label">Name of Driver</label>
                    <input type="text" id="driverName" class="form-control" name="name" value="<?php echo $drivername?>" required>

                    <div class="form-check">
                        <label for="radioOption1" class="form-check-label">Male</label>
                        <input type="radio" id="radioOption1" class="form-check-input" name="sex" value="Male" <?php echo $sex == 'Male' ? 'checked' : ''?>>
                    </div>

                    <div class="form-check">
                        <label for="radioOption2" class="form-check-label">Female</label>
                        <input type="radio" id="radioOption2" class="form-check-input" name="sex" value="Female" <?php echo $sex == 'Female' ? 'checked' : ''?>>
                    </div>

                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" class="form-control" name="address" value="<?php echo $driveraddress?>" required>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <input type="text" name="serial-number" value="<?php echo $serialnum?>" style="display: none;">
                        <button type="submit" class="btn btn-primary" name="updateLicenseInformation" value="true">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="deleteLicenseRecordModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Unregister license with serial number <?php echo $serialnum?>? [NOTE: THIS WILL DELETE ALL RELATED RECORDS]</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="_functions.php">
                        <input type="text" name="serial-number" value="<?php echo $serialnum ?>" style="display: none;">
                        <input type="text" name="license-number" value="<?php echo $licensenum ?>" style="display: none">
                        <button type="submit" class="btn btn-primary" name="deleteLicense" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>