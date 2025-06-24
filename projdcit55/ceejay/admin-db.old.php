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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
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
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </div>
        </nav>

    </header>

    <div class="container">

        <div class="row">
            <div class="col">
                <h3>License Registration</h3>
                <form method="post" action="_functions.php">

                    <label for="driverName" class="form-label">Name of Driver</label>
                    <input type="text" id="driverName" class="form-control" name="name" required>

                    <div class="form-check form-check-inline">
                        <label for="radioOption1" class="form-check-label">Male</label>
                        <input type="radio" id="radioOption1" class="form-check-input" name="sex" value="Male" required>
                    </div>

                    <div class="form-check form-check-inline">
                        <label for="radioOption2" class="form-check-label">Female</label>
                        <input type="radio" id="radioOption2" class="form-check-input" name="sex" value="Female">
                    </div>

                    <div class="form-check form-check-inline"></div>

                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" class="form-control" name="address" required>

                    <label for="licenseNumber" class="form-label">License Number</label>
                    <input type="text" id="licenseNumber" class="form-control" name="license-number" minlength="8" maxlength="8" required>

                    <button type="submit" class="btn btn-primary" name="register-license" style="margin-top: 0.5rem;">Register</button>

                </form>
            </div>
            <div class="col-9">
                <div class="row">

                    <div class="col-9">
                        <input type="text" id="filterSearch" placeholder="search records by name..." class="form-control">
                    </div>

                    <div class="col">
                        <select id="filterSelect" class="form-select">
                            <option value="All" selected>All</option>
                            <option value="Valid">Valid</option>
                            <option value="Suspended">Suspended</option>
                            <option value="Revoked">Revoked</option>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Sex</th>
                            <th>Address</th>
                            <th>License Number</th>
                            <th>Date Registered</th>
                            <th>Date Renewed</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        <?php viewAllLicense()?>
                    </table>
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
                    <h5 class="modal-title">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="_functions.php">
                        <button type="submit" class="btn btn-danger" name="logoutAdmin" value="true">Confirm</button>
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