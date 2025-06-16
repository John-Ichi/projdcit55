<?php
include '_functions.php';
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
</head>
<body>

    <div class="container">

    <nav class="navbar">
        <a class="navbar-brand" href="#">ProjectLisensya</a>
    </nav>

    <h3>Register License</h3>
        
        <div class="row">
            
            <div class="col">

                <form method="post" action="_functions.php">

                    <label for="driverName" class="form-label">Name of Driver</label>
                    <input type="text" id="driverName" class="form-control" name="name" required>

                    <div class="form-check">
                        <label for="radioOption1" class="form-check-label">Male</label>
                        <input type="radio" id="radioOption1" class="form-check-input" name="sex" value="Male" required>
                    </div>

                    <div class="form-check">
                        <label for="radioOption2" class="form-check-label">Female</label>
                        <input type="radio" id="radioOption2" class="form-check-input" name="sex" value="Female">
                    </div>

                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" class="form-control" name="address" required>

                    <label for="licenseNumber" class="form-label">License Number</label>
                    <input type="text" id="licenseNumber" class="form-control" name="license-number" minlength="8" maxlength="8" required>

                    <button type="submit" class="btn btn-primary" name="register-license">Register</button>
                </form>

            </div>

            <div class="col">
                <p>tbc what to put</p>
            </div>
        
        </div>

        <div class="row">
            <div class="col">
                <label for="filterSearch" class="form-label">search by name</label>
                <input type="text" id="filterSearch" class="form-control">
            </div>
            <div class="col">
                <label for="filterSelect" class="form-label">filter by status</label>
                <select id="filterSelect" class="form-control">
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
        </div>
    
            <?php viewAllLicense(); ?>
        </table>

        <div class="row">
            <a href="index.php">Logout</a>
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
                    if (item.querySelector('td:nth-child(8') == null) {
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