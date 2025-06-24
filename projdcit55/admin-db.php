<?php
include '_functions.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
}

updateLicenseExpired();
checkSuspensionRevocationDeadlines();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProjectLisensya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
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
            <button class="btn btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </div>
        </nav>

    </header>

    <div class="container">

        <div class="main-flex-row">
            <div class="sidebar card-style" style="min-height: 550px;">
                <h3><i class="bi bi-person-plus me-2"></i>License Registration</h3>
                <form method="post" action="_functions.php" autocomplete="off">
                    <label for="driverName" class="form-label"><i class="bi bi-person me-1"></i>Name of Driver</label>
                    <input type="text" id="driverName" class="form-control" name="name" required>
                    <div class="form-check form-check-inline">
                        <label for="radioOption1" class="form-check-label"><i class="bi bi-gender-male me-1"></i>Male</label>
                        <input type="radio" id="radioOption1" class="form-check-input" name="sex" value="Male" required>
                    </div>
                    <div class="form-check form-check-inline">
                        <label for="radioOption2" class="form-check-label"><i class="bi bi-gender-female me-1"></i>Female</label>
                        <input type="radio" id="radioOption2" class="form-check-input" name="sex" value="Female">
                    </div>
                    <div class="form-check form-check-inline"></div>
                    <div></div>
                    <label for="address" class="form-label"><i class="bi bi-geo-alt me-1"></i>Address</label>
                    <input type="text" id="address" class="form-control" name="address" required>
                    <label for="licenseNumber" class="form-label"><i class="bi bi-credit-card-2-front me-1"></i>License Number</label>
                    <input type="text" id="licenseNumber" class="form-control" name="license-number" minlength="8" maxlength="8" required>
                    <button type="submit" class="btn btn-primary" name="register-license" style="margin-top: 0.5rem;"><i class="bi bi-plus-circle me-1"></i>Register</button>
                </form>
            </div>
            <div class="flex-main-content">
                <div class="row card-style">
                    <div class="col-9">
                        <input type="text" id="filterSearch" placeholder="Search records by name..." class="form-control" aria-label="Search by name">
                    </div>
                    <div class="col">
                        <select id="filterSelect" class="form-select" aria-label="Filter by status">
                            <option value="All" selected><i class="bi bi-list"></i> All</option>
                            <option value="Valid"><i class="bi bi-check-circle"></i> Valid</option>
                            <option value="Suspended"><i class="bi bi-pause-circle"></i> Suspended</option>
                            <option value="Revoked"><i class="bi bi-x-circle"></i> Revoked</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive" style="height: 390px; overflow-y: auto;">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 110px;">Name</th>
                                    <th scope="col" style="width: 70px;">Sex</th>
                                    <th scope="col" style="width: 100px;">Address</th>
                                    <th scope="col" style="width: 93px; white-space: normal;">License Number</th>
                                    <th scope="col" style="width: 121px; white-space: normal;">Date Registered</th>
                                    <th scope="col" style="width: 101px; white-space: normal;">Date Renewed</th>
                                    <th scope="col" style="width: 116px; white-space: normal;">Expiration Date</th>
                                    <th scope="col" style="width: 91px;">Status</th>
                                    <th scope="col" style="width: 126px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php viewAllLicense(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="d-flex justify-content-center">

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
                        <button type="submit" class="btn btn-danger" name="logoutAdmin" value="true"><i class="bi bi-check-lg me-1"></i>Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterSearch = document.getElementById('filterSearch');
        filterSearch.addEventListener('input', function() {
            let filterSearchValue = filterSearch.value.toLowerCase();
            const tableRows = document.querySelectorAll('tr');
            tableRows.forEach(item => {
                if (filterSearchValue == '') {
                    item.style.display = 'table-row';
                } else {
                    if (item.querySelector('td:nth-child(1)') == null) {
                        return null;
                    } else {
                        let name = item.querySelector('td:nth-child(1)').innerHTML.toLowerCase();
                        if (name.includes(filterSearchValue)) {
                            console.log(name);
                            item.style.display = 'table-row';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                }
            });
        });

        const filterSelect = document.getElementById('filterSelect');
        filterSelect.addEventListener('change', function() {
            const filterSelectValue = filterSelect.value;
            const tableRows = document.querySelectorAll('tr');
            tableRows.forEach(item => {
                if (filterSelectValue == 'All') {
                    item.style.display = 'table-row';
                } else {
                    if (item.querySelector('td:nth-child(8)') == null) {
                        return null;
                    } else {
                        if (item.querySelector('td:nth-child(8)').innerHTML == filterSelectValue) {
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