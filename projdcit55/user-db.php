<?php
include '_functions.php';

if (!isset($_SESSION['driver'])) {
    header('Location: login.php');
} else {
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
    <link rel="stylesheet" href="styles/dashboard.css">
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
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </div>
        </nav>
    </header>

    <!-- Page Header -->
    <div class="container">
        <div class="main-flex-row">
            <div class="sidebar card-style" style="min-height: 550px;">

            </div>
            <div class="flex-main-content">
                <div class="card-style mb-4 flex-grow-1">
                    <form method="post" action="_functions.php" style="margin:0;padding:0;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Violation Records</h3>
                            <div class="d-flex align-items-center gap-2">
                                <select id="filterSelect" class="form-select" style="width: 150px;">
                                    <option value="All" selected>All</option>
                                    <option value="No">Not Resolved</option>
                                    <option value="Yes">Resolved</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle" style="height: 408.5px; max-height: 408.5px; display: block; overflow-y: auto;">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 60px;">No</th>
                                        <th scope="col" style="width: 230px;">Violation Committed</th>
                                        <th scope="col" style="width: 260px;">Penalty</th>
                                        <th scope="col" style="width: 255px;">Deadline for Settlement</th>
                                        <th scope="col" style="width: 80px;">Resolved</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody"></tbody>
                            </table>
                        </div>
                    </form>
                </div>
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
        getInfo();
        getViolations();
        checkDeleted();

        function checkDeleted() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (xhttp.responseText == 'deleted') {
                        alert('Your license has been terminated');
                        window.location.href = 'index.php';
                    }
                }
            };
            xhttp.open("GET","_check-deleted.php",true);
            xhttp.send();
        }

        function getInfo() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.querySelector('.sidebar').innerHTML = xhttp.responseText;
                }
            };
            xhttp.open("GET","_server-driver-info.php",true);
            xhttp.send();
        }

        function getViolations() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('tableBody').innerHTML = xhttp.responseText;
                    filterTable();
                }
            };
            xhttp.open("GET","_server-driver.php",true);
            xhttp.send();
        }

        setInterval(function() {
            getInfo();
            getViolations();
            checkDeleted();
        },1000);

        const filterSelect = document.getElementById('filterSelect');
        filterSelect.addEventListener('change', filterTable);

        function filterTable() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(tr => {
                let resolvedCell = tr.querySelector('td:nth-child(5)');

                if (filterSelect.value != resolvedCell.innerHTML) {
                    tr.style.display = 'none';
                } else {
                    tr.style.display = 'table-row';
                }

                if (filterSelect.value === 'All') {
                    tr.style.display = 'table-row';
                }
            });
        }
    </script>

</body>
</html>