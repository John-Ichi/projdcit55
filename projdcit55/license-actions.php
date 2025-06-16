<?php
include '_functions.php';
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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="app-header">
        <div class="header-content">
            <div class="logo-container">
                <i class="fas fa-id-card logo-icon"></i>
                <h1 class="logo-text">Project<span>Lisensya</span></h1>
            </div>
            <nav class="header-actions">
                <a href="admin-db.php" class="return-btn">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </nav>
        </div>
    </header>

    <main class="app-container">
        <section class="driver-profile">
            <div class="profile-header">
                <div class="driver-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="driver-meta">
                    <h2><?php echo $drivername; ?></h2>
                    <p class="license-badge">License: <?php echo $licensenum; ?></p>
                    <p class="status-badge status-<?php echo strtolower($status); ?>">
                        <?php echo $status; ?>
                    </p>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-card">
                    <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Sex</span>
                            <span class="detail-value"><?php echo $sex; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Address</span>
                            <span class="detail-value"><?php echo $driveraddress; ?></span>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <h3><i class="fas fa-calendar-alt"></i> License Dates</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Registered</span>
                            <span class="detail-value"><?php echo $dateregistered; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Renewed</span>
                            <span class="detail-value"><?php echo $daterenewed; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Expires</span>
                            <span class="detail-value"><?php echo $expirationdate; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="action-section">
            <h3><i class="fas fa-cog"></i> License Actions</h3>
            <div class="action-grid">
                <?php if ($status == 'Revoked'): ?>
                    <button class="action-btn success" data-modal="unrevokeModal">
                        <i class="fas fa-undo"></i> Unrevoke
                    </button>
                <?php elseif ($status == 'Suspended'): ?>
                    <button class="action-btn success" data-modal="unsuspendModal">
                        <i class="fas fa-undo"></i> Unsuspend
                    </button>
                <?php else: ?>
                    <button class="action-btn primary" data-modal="renewModal">
                        <i class="fas fa-sync-alt"></i> Renew
                    </button>
                    <button class="action-btn warning" data-modal="suspendModal">
                        <i class="fas fa-pause-circle"></i> Suspend
                    </button>
                <?php endif; ?>
                
                <?php if ($status != 'Revoked'): ?>
                    <button class="action-btn danger" data-modal="revokeModal">
                        <i class="fas fa-ban"></i> Revoke
                    </button>
                <?php endif; ?>
                
                <button class="action-btn secondary" data-modal="fileViolationModal">
                    <i class="fas fa-exclamation-triangle"></i> File Violation
                </button>
                <button class="action-btn secondary" data-modal="editInformationModal">
                    <i class="fas fa-edit"></i> Edit Information
                </button>
                <button class="action-btn danger" data-modal="deleteLicenseRecordModal">
                    <i class="fas fa-trash-alt"></i> Delete Record
                </button>
            </div>
        </section>

        <section class="violations-section">
            <div class="section-header">
                <h3><i class="fas fa-clipboard-list"></i> Violation History</h3>
            </div>
            <div class="table-container">
                <table class="violations-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Violation</th>
                            <th>Penalty</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php viewLicenseViolations($serialnum); ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div class="modal" id="renewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-sync-alt"></i> License Renewal</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Extend validity by 5 years for license with serial number <strong><?php echo $serialnum; ?></strong>?</p>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <form method="post" action="_functions.php">
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                    <input type="hidden" name="date-of-expiry" value="<?php echo $expirationdate; ?>">
                    <button type="submit" class="btn primary" name="renewLicense" value="true">
                        <i class="fas fa-check"></i> Confirm Renewal
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="suspendModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-pause-circle"></i> Suspend License</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to suspend license <strong><?php echo $licensenum; ?></strong>?</p>
                <form method="post" action="_functions.php" class="suspend-form">
                    <div class="form-group">
                        <label for="suspendReason">Reason for suspension:</label>
                        <textarea id="suspendReason" name="suspend-reason" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="suspendDuration">Duration (days):</label>
                        <input type="number" id="suspendDuration" name="suspend-duration" min="1" required>
                    </div>
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <button type="submit" class="btn warning" form="suspend-form" name="suspendLicense" value="true">
                    <i class="fas fa-check"></i> Confirm Suspension
                </button>
            </div>
        </div>
    </div>

    <div class="modal" id="unsuspendModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-undo"></i> Unsuspend License</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unsuspend license <strong><?php echo $licensenum; ?></strong>?</p>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <form method="post" action="_functions.php">
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                    <button type="submit" class="btn success" name="unsuspendLicense" value="true">
                        <i class="fas fa-check"></i> Confirm Unsuspension
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="revokeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-ban"></i> Revoke License</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently revoke license <strong><?php echo $licensenum; ?></strong>?</p>
                <form method="post" action="_functions.php" class="revoke-form">
                    <div class="form-group">
                        <label for="revokeReason">Reason for revocation:</label>
                        <textarea id="revokeReason" name="revoke-reason" required></textarea>
                    </div>
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <button type="submit" class="btn danger" form="revoke-form" name="revokeLicense" value="true">
                    <i class="fas fa-check"></i> Confirm Revocation
                </button>
            </div>
        </div>
    </div>
    <div class="modal" id="unrevokeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-undo"></i> Unrevoke License</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unrevoke license <strong><?php echo $licensenum; ?></strong>?</p>
                <p class="warning-text">This will restore the license to active status.</p>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <form method="post" action="_functions.php">
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                    <button type="submit" class="btn success" name="unrevokeLicense" value="true">
                        <i class="fas fa-check"></i> Confirm Unrevocation
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="fileViolationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> File Violation</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="_functions.php" class="violation-form">
                    <div class="form-group">
                        <label for="violationType">Violation Type:</label>
                        <select id="violationType" name="violation-type" required>
                            <option value="">Select violation type</option>
                            <option value="Speeding">Speeding</option>
                            <option value="Reckless Driving">Reckless Driving</option>
                            <option value="DUI">Driving Under Influence</option>
                            <option value="Unregistered Vehicle">Unregistered Vehicle</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="violationDescription">Description:</label>
                        <textarea id="violationDescription" name="violation-description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="violationPenalty">Penalty:</label>
                        <select id="violationPenalty" name="violation-penalty" required>
                            <option value="">Select penalty</option>
                            <option value="Fine">Fine</option>
                            <option value="Suspension">License Suspension</option>
                            <option value="Community Service">Community Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="violationDeadline">Deadline:</label>
                        <input type="date" id="violationDeadline" name="violation-deadline" required>
                    </div>
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                    <input type="hidden" name="license-number" value="<?php echo $licensenum; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <button type="submit" class="btn primary" form="violation-form" name="fileViolation" value="true">
                    <i class="fas fa-check"></i> File Violation
                </button>
            </div>
        </div>
    </div>

    <div class="modal" id="editInformationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Driver Information</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="_functions.php" class="edit-form">
                    <div class="form-group">
                        <label for="editDriverName">Driver Name:</label>
                        <input type="text" id="editDriverName" name="driver-name" value="<?php echo $drivername; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editDriverAddress">Address:</label>
                        <textarea id="editDriverAddress" name="driver-address" required><?php echo $driveraddress; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editDriverSex">Sex:</label>
                        <select id="editDriverSex" name="driver-sex" required>
                            <option value="Male" <?php echo $sex == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $sex == 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <button type="submit" class="btn primary" form="edit-form" name="editDriverInfo" value="true">
                    <i class="fas fa-check"></i> Save Changes
                </button>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteLicenseRecordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-trash-alt"></i> Delete License Record</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete the license record for <strong><?php echo $drivername; ?></strong>?</p>
                <p class="warning-text">This action cannot be undone. All associated data including violations will be deleted.</p>
            </div>
            <div class="modal-footer">
                <button class="btn secondary close-modal">Cancel</button>
                <form method="post" action="_functions.php">
                    <input type="hidden" name="serial-number" value="<?php echo $serialnum; ?>">
                    <button type="submit" class="btn danger" name="deleteLicenseRecord" value="true">
                        <i class="fas fa-check"></i> Confirm Deletion
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>