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
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg custom-header">
          <div class="container-fluid header-flex">
            <a class="navbar-brand logo-brand" href="#">
              <span class="logo-icon"><i class="bi bi-shield-check"></i></span>
              <span class="logo-text">Project<span class="logo-accent">Lisensya</span></span>
            </a>
            <div class="header-center">
              <ul class="navbar-nav flex-row gap-3">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i>Login</a></li>
                <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-person-gear"></i>Admin</a></li>
              </ul>
            </div>
            <a href="admin-db.php" class="btn btn-logout">
              <i class="bi bi-arrow-left"></i>Return
            </a>
          </div>
        </nav>
    </header>

    <div class="container">
        <div class="main-flex-row">
            <!-- License Information Sidebar -->
            <div class="sidebar card-style" style="min-height: 550px;">
                <h3><i class="bi bi-person-badge me-2"></i>License Information</h3>
                
                <div class="license-info">
                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-person me-1"></i>Driver Name</label>
                        <div class="info-value"><?php echo $drivername; ?></div>
                        <small class="text-muted">[<?php echo $serialnum; ?>]</small>
                    </div>

                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-credit-card-2-front me-1"></i>License Number</label>
                        <div class="info-value"><?php echo $licensenum; ?></div>
                    </div>

                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-info-circle me-1"></i>Status</label>
                        <div class="info-value status-<?php echo $status; ?>"><?php echo $status; ?></div>
                    </div>

                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-gender-ambiguous me-1"></i>Sex</label>
                        <div class="info-value"><?php echo $sex; ?></div>
                    </div>

                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-geo-alt me-1"></i>Address</label>
                        <div class="info-value"><?php echo $driveraddress; ?></div>
                    </div>

                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-calendar-plus me-1"></i>Date Registered</label>
                        <div class="info-value"><?php echo $dateregistered; ?></div>
                    </div>

                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-arrow-repeat me-1"></i>Date Renewed</label>
                        <div class="info-value"><?php echo $daterenewed; ?></div>
                    </div>

                    <div class="info-group">
                        <label class="form-label"><i class="bi bi-calendar-x me-1"></i>Expiration Date</label>
                        <div class="info-value"><?php echo $expirationdate; ?></div>
                    </div>
                </div>

                <h4 class="mt-4 mb-3"><i class="bi bi-gear me-2"></i>Actions</h4>
                <div class="action-buttons">
                    <?php
                    if ($status == 'Revoked') {
                        echo '<button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#unrevokeModal"><i class="bi bi-check-circle me-1"></i>Unrevoke</button>';
                    } else {
                        if ($status == 'Suspended') {
                            echo '<button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#unsuspendModal"><i class="bi bi-play-circle me-1"></i>Unsuspend</button>';
                        } else {
                            echo '<button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#renewModal"><i class="bi bi-arrow-repeat me-1"></i>Renew</button>';
                            echo '<button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#suspendModal"><i class="bi bi-pause-circle me-1"></i>Suspend</button>';
                        }
                        echo '<button class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#revokeModal"><i class="bi bi-x-circle me-1"></i>Revoke</button>';
                    }
                    ?>
                    <button class="btn btn-info w-100 mb-2" data-bs-toggle="modal" data-bs-target="#fileViolationModal"><i class="bi bi-exclamation-triangle me-1"></i>File Violation</button>
                    <button class="btn btn-secondary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editInformationModal"><i class="bi bi-pencil me-1"></i>Edit Information</button>
                    <button class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#deleteLicenseRecordModal"><i class="bi bi-trash me-1"></i>Delete Record</button>
                </div>
            </div>

            <!-- Violations Table -->
            <div class="flex-main-content">
                <div class="card-style">
                    <h3><i class="bi bi-exclamation-triangle me-2"></i>Violation Records</h3>
                    <table class="table align-middle">
                        <tr>
                            <th><i class="bi bi-hash"></i>ID</th>
                            <th><i class="bi bi-exclamation-triangle"></i>Violation</th>
                            <th><i class="bi bi-currency-dollar"></i>Penalty</th>
                            <th><i class="bi bi-calendar-event"></i>Deadline for Settlement</th>
                            <th><i class="bi bi-check-circle"></i>Resolved</th>
                            <th class="text-center"><i class="bi bi-gear"></i>Actions</th>
                        </tr>
                        <?php viewLicenseViolations($serialnum)?>
                    </table>
                </div>
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