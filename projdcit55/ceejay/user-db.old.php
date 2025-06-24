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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        table.table {
            height: 300px;
            max-height: 300px;
            display: block;
            overflow-y: auto;
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
                            <a class="nav-link active" aria-current="page" href="#">Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Admin</a>
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
                <div class="row">
                    <p><b>ID:</b> <?php echo $userid?></p>
                </div>
                <div class="row">
                    <p><b>Name:</b> <?php echo $drivername?></p>
                </div>
                <div class="row">
                    <p><b>Sex:</b> <?php echo $sex?></p>
                </div>
                <div class="row">
                    <p><b>License Number:</b> <?php echo $licensenum?></p>
                </div>
                <div class="row">
                    <p><b>Status:</b> <?php echo $status?></p>
                </div>
                <div class="row">
                    <p><b>Date Registered:</b> <?php echo $dateregistered?></p>
                </div>
                <div class="row">
                    <p><b>Date Renewed:</b> <?php echo $daterenewed?></p>
                </div>
                <div class="row">
                    <p><b>Expiration Date:</b> <?php echo $expirationdate?></p>
                </div>
                <div class="row">
                    <p><b>Address:</b> <?php echo $driveraddress?></p>
                </div>
            </div>
            <div class="col-9">
                <div class="row">
                    <div class="d-flex justify-content-center">
                        <label for="filterSelect" class="form-label">sort by resolved</label>
                    </div>
                    <select id="filterSelect" class="form-select">
                        <option value="All" selected>All</option>
                        <option value="No">Not Resolved</option>
                        <option value="Yes">Resolved</option>
                    </select>
                </div>
                <div class="row" id="userViolations">
                <!--<table class="table">
                        <tr>
                            <th></th>
                            <th>Violation Committed</th>
                            <th>Penalty</th>
                            <th>Deadline for Settlement</th>
                            <th>Resolved</th>
                        </tr>
                        <?php // viewLicenseViolationsUser($licensenum)?>
                    </table>-->
                </div>
            </div>
        </div>

    </div>

    <footer class="d-flex justify-content-center">
        <p>Visit the nearest ProjectLisensya Office (PLO) near you to resolve violations.</p>
    </footer>

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
                        <button type="submit" class="btn btn-danger" name="logoutDriver" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>

        function getViolations() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('userViolations').innerHTML = xhttp.responseText;
                }
            };
            xhttp.open("GET","_server-driver.php",true);
            xhttp.send();
        }

        /* setInterval(function() {
            getViolations();
        },1000); */

        getViolations();

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