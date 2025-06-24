<?php
include '_functions.php';

if (!isset($_SESSION['driver'])) {
    header('Location: login.php');
} else {
    $info = getLicenseInfoUser($_SESSION['driver']);
    $drivername = $info[0]['name'];
    $sex = $info[0]['sex'];
    $driveraddress = $info[0]['address'];
    $licensenum = $info[0]['licenseNumber'];
    $dateregistered = $info[0]['dateRegistered'];
    $daterenewed = $info[0]['dateRenewed'] == null ? 'no record' : $info[0]['dateRenewed'];
    $expirationdate = $info[0]['expirationDate'];
    $status = $info[0]['status'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProjectLisensya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="dashboard.css">
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
                <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-person-circle"></i>Account</a></li>
                <li class="nav-item"><a class="nav-link" href="admin.php"><i class="bi bi-person-gear"></i>Admin</a></li>
              </ul>
            </div>
            <button class="btn btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
              <i class="bi bi-box-arrow-right"></i>Logout
            </button>
          </div>
        </nav>
    </header>

    <!-- Page Header -->
    <div class="page-header text-center mb-4">
        <div class="main-title"><i class="bi bi-shield-check me-2"></i>ProjectLisensya User Dashboard</div>
        <div class="subtitle">Welcome! Here you can view your license details and violation records.</div>
    </div>
    <div style="display: flex; gap: 2rem; max-width: 1100px; margin: 2rem auto; flex-wrap: wrap; align-items: flex-start;">
        <!-- User Info Card -->
        <div style="min-width: 320px; max-width: 380px; flex: 0 0 350px;">
            <div class="card-style mb-4 h-100">
                <h3 class="mb-3"><i class="bi bi-person-circle me-2"></i>Driver Information</h3>
                <div class="driver-info-fields">
                  <div><i class="bi bi-person me-2"></i><b>Name:</b> <?php echo $drivername?></div>
                  <div><i class="bi bi-gender-ambiguous me-2"></i><b>Sex:</b> <?php echo $sex?></div>
                  <div><i class="bi bi-credit-card-2-front me-2"></i><b>License Number:</b> <?php echo $licensenum?></div>
                  <div><i class="bi bi-info-circle me-2"></i><b>Status:</b> <span class="status-<?php echo $status; ?>"><?php echo $status?></span></div>
                  <div><i class="bi bi-calendar-plus me-2"></i><b>Date Registered:</b> <?php echo $dateregistered?></div>
                  <div><i class="bi bi-arrow-repeat me-2"></i><b>Date Renewed:</b> <?php echo $daterenewed?></div>
                  <div><i class="bi bi-calendar-x me-2"></i><b>Expiration Date:</b> <?php echo $expirationdate?></div>
                  <div><i class="bi bi-geo-alt me-2"></i><b>Address:</b> <?php echo $driveraddress?></div>
                </div>
            </div>
        </div>
        <!-- Violations Card (form wraps filter and table) -->
        <div style="flex: 1 1 0; display: flex; flex-direction: column; min-width: 0;">
            <div class="card-style mb-4 flex-grow-1">
                <form method="post" action="_functions.php" style="margin:0;padding:0;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Violation Records</h3>
                        <div class="d-flex align-items-center gap-2">
                            <label for="filterSelect" class="form-label mb-0 me-2">Sort by resolved</label>
                            <select id="filterSelect" class="form-select" style="width: 150px;">
                                <option value="All" selected>All</option>
                                <option value="No">Not Resolved</option>
                                <option value="Yes">Resolved</option>
                            </select>
                        </div>
                    </div>
                    <table class="table align-middle">
                        <tr>
                            <th>#</th>
                            <th>Violation Committed</th>
                            <th>Penalty</th>
                            <th>Deadline for Settlement</th>
                            <th>Resolved</th>
                        </tr>
                        <?php viewLicenseViolationsUser($licensenum)?></table>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="logoutModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-box-arrow-right me-2"></i>Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancel</button>
                    <form action="_functions.php">
                        <button type="submit" class="btn btn-danger" name="logoutDriver" value="true"><i class="bi bi-check-lg me-1"></i>Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterSelect = document.getElementById('filterSelect');
        filterSelect.addEventListener('change', function() {
            const filterSelectValue = filterSelect.value;
            const tableRows = document.querySelectorAll('tr');
            tableRows.forEach(item => {
                if (filterSelectValue == 'All') {
                    item.style.display = 'table-row';
                } else {
                    if (item.querySelector('td:nth-child(5)') == null) {
                        return null;
                    } else {
                        if (item.querySelector('td:nth-child(5)').innerHTML == filterSelectValue) {
                            item.style.display = 'table-row';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                }
            });
        });
    </script>

</body>
</html>