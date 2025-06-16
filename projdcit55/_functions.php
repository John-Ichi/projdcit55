<?php

function connect() {
    $conn = new mysqli('localhost','root','','dcit55_project');
    return $conn;
}

$conn = connect();
session_start();

function returnToLicenseActions($serial_number, $alertmessage) {
    echo "
        <fieldset style='display: none;'>
            <form action='license-actions.php' id='return'>
                <input type='text' name='serial-number' value='" . $serial_number . "' style='display:none;'>
            </form>
        </fieldet>
        <script>
            alert('" . $alertmessage ."');
            document.getElementById('return').submit();
        </script>    
    ";
}

// User actions
function checkDuplicateLicense($license_number) { // Check duplicate license
    $conn = connect();
    $sql =
        "SELECT licenseNumber
        FROM tblicense
        WHERE licenseNumber='$license_number'";
    $rs = $conn->query($sql);

    if ($rs->num_rows != 0) return true;
    else return false;
}

function checkAccountExists($license_number) {
    $conn = connect();
    $sql =
        "SELECT licenseNumber
        FROM tblogininfo
        WHERE licenseNumber='$license_number'";
    $rs = $conn->query($sql);

    if ($rs->num_rows != 0) return true;
    else return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-driver'])) { // Register driver account
    $license_number = $_POST['license-number'];
    $check_duplicate = checkDuplicateLicense($license_number);

    if ($check_duplicate == false) {
        echo "
            <script>
                alert('License does not exist!');
                window.location.href = 'index.php';
            </script>
        ";
        return;
    }

    $check_account = checkAccountExists($license_number);
    if ($check_account == true) {
        echo "
            <script>
                alert('Account already exists!');
                window.location.href = 'index.php';
            </script>
        ";
        return;
    }

    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql =
        "INSERT INTO tblogininfo
        (`licenseNumber`,`password`)
        VALUES
        (?,?)";
    $register = $conn->prepare($sql);
    $register->bind_param('ss',$license_number,$hashed_password);
    $register->execute();

    echo "
        <script>
            alert('Account created successfully!');
            window.location.href = 'index.php';
        </script>
    ";
}

function checkRegisteredDriver($license_number) { // Check if driver registered an account for their license
    $conn = connect();
    $sql =
        "SELECT licenseNumber
        FROM tblogininfo
        WHERE licenseNumber='$license_number'";
    $rs = $conn->query($sql);

    if ($rs->num_rows != 0) return true;
    else return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['driver-login'])) { // Driver login
    $license_number = $_POST['license-number'];
    $check_registered = checkRegisteredDriver($license_number);

    if ($check_registered == false) { // Check if license is registered
        echo "
            <script>
                alert('License does not exist!');
                window.location.href = 'index.php';
            </script>
        ";
        return;
    } else if ($check_registered == true) {
        $sql =
            "SELECT * FROM tblogininfo";
        $rs = $conn->query($sql);
        $info = $rs->fetch_assoc();

        if (password_verify($_POST['password'],$info['password'])) {
            $_SESSION['driver'] = $license_number;
            header('Location: user-db.php');
        }
    }
}

function getLicenseInfoUser($license_number) {
    $conn = connect();
    $sql =
        "SELECT name, sex, address, licenseNumber, dateRegistered, dateRenewed, expirationDate, status
        FROM tblicense
        WHERE licenseNumber='$license_number'";
    $rs = $conn->query($sql);
    
    while ($row = $rs->fetch_assoc()) {
        $info[] = $row;
    }

    return $info;
}

function viewLicenseViolationsUser($license_number) {
    $conn = connect();
    $sql =
        "SELECT * FROM tbviolations WHERE licenseNumber='$license_number'";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        echo "
            <tr>
                <td colspan='5' style='text-align: center;'><i>no violations</td>
            </tr>
        ";
    }

    $count = 1;

    while ($row = $rs->fetch_assoc()) {
        echo "
            <tr>
                <td>" . $count ."</td>
                <td>" . $row['violationCommitted'] ."</td>
                <td>" . $row['penaltyAlloted'] ."</td>
                <td>" . $row['settlementDeadline'] ."</td>
                <td>" . ($row['resolved'] == 0 ? 'No' : 'Yes') ."</td>
            </tr>
        ";
        $count++;
    }
}

if (isset($_GET['logoutDriver']) && $_GET['logoutDriver'] == true) { // Driver logout
    unset($_SESSION['driver']);
    header('Location: login.php');
}

// Admin functions
function checkRegisteredLicense($serial_number) { // For license-actions
    $conn = connect();
    $sql =
        "SELECT serialNumber
        FROM tblicense
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);

    if ($rs->num_rows != 0) return true;
    else return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-license'])) { // Register driver's license
    $license_number = $_POST['license-number'];
    $check_duplicate = checkDuplicateLicense($license_number);

    if ($check_duplicate == true) {
        echo "
            <script>
                alert('Duplicate license number found!');
                window.location.href = 'admin-db.php';
            </script>
        ";
        return;
    }

    $date = date_create(date("Y-m-d"));
    date_add($date,date_interval_create_from_date_string("5 years"));
    $expiration_date = date_format($date,"Y-m-d");

    $sql =
        "INSERT INTO tblicense
        (`name`,`sex`,`address`,`licenseNumber`,`expirationDate`)
        VALUES
        (?,?,?,?,?)";
    $register = $conn->prepare($sql);
    $register->bind_param('sssss',$_POST['name'],$_POST['sex'],$_POST['address'],$_POST['license-number'],$expiration_date);
    $register->execute();

    echo "
        <script>
            alert('Registered successfully!');
            window.location.href = 'admin-db.php';
        </script>
    ";
}

if (isset($_POST['adminLogin']) && $_POST['adminLogin'] == true) { // Admin login
    $admin = $_POST['username'];
    $password = $_POST['password'];
    $sql =
        "SELECT *
        FROM tbadmin
        WHERE admin='$admin'";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        echo "
            <script>
                alert('Admin does not exist!');
                window.location.href = 'admin.php';
            </script>
        ";
    } else {
        while ($row = $rs->fetch_assoc()) {
            if ($password != $row['password']) {
                echo "
                    <script>
                        alert('Incorrect password!');
                        window.location.href = 'admin.php';
                    </script>
                ";
            } else {
                $_SESSION['admin'] = $admin;
                header('Location: admin-db.php');
            }
        }
    }
}

if (isset($_GET['logoutAdmin']) && $_GET['logoutAdmin'] == true) { // Logout admin
    unset($_SESSION['admin']);
    header('Location: admin.php');
}

// License actions
if (isset($_POST['renewLicense']) && $_POST['renewLicense'] == true) { // Extend license expiration
    $serial_number = $_POST['serial-number'];
    $prev_expiry = $_POST['date-of-expiry'];
    $date = date_create($prev_expiry);
    date_add($date,date_interval_create_from_date_string("5 years"));
    $new_expiry = date_format($date,"Y-m-d");

    $sql =
        "UPDATE tblicense
        SET dateRenewed='" . date("Y-m-d") . "',expirationDate='$new_expiry',status='Valid'
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);

    returnToLicenseActions($serial_number, "License renewed!");
}

if (isset($_POST['suspendLicense']) && $_POST['suspendLicense'] == true) { // Suspend license
    $serial_number = $_POST['serial-number'];
    $sql =
        "UPDATE tblicense
        SET status='Suspended'
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);

    returnToLicenseActions($serial_number,"License suspended!");
}

if (isset($_POST['unsuspendLicense']) && $_POST['unsuspendLicense'] == true) { // Unuspend license
    $serial_number = $_POST['serial-number'];

    $check = checkUnresolvedViolations($serial_number);
    if ($check == true) {
        returnToLicenseActions($serial_number,"Unresolved violation(s)!");
        return;   
    }

    $sql =
        "UPDATE tblicense
        SET status='Valid'
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);

    returnToLicenseActions($serial_number,"License unsuspended!");
}

if (isset($_POST['revokeLicense']) && $_POST['revokeLicense'] == true) { // Revoke license
    $serial_number = $_POST['serial-number'];
    $sql =
        "UPDATE tblicense
        SET status='Revoked'
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);

    returnToLicenseActions($serial_number,"License revoked!");
}

if (isset($_POST['unrevokeLicense']) && $_POST['unrevokeLicense'] == true) { // Unrevoke license
    $serial_number = $_POST['serial-number'];

    $check = checkUnresolvedViolations($serial_number);
    if ($check == true) {
        returnToLicenseActions($serial_number,"Unresolved violation(s)!");
        return;   
    }

    $sql =
        "UPDATE tblicense
        SET status='Valid'
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);

    returnToLicenseActions($serial_number,"License unrevoked!");
}

if (isset($_POST['fileViolation']) && $_POST['fileViolation'] == true) { // File violation
    $serial_number = $_POST['serial-number'];
    $sql =
        "INSERT INTO tbviolations
        (`licenseSN`,`licenseNumber`,`violationCommitted`,`penaltyAlloted`,`settlementDeadline`)
        VALUES
        (?,?,?,?,?)";
    $file_violation = $conn->prepare($sql);;
    $file_violation->bind_param('sssss',$serial_number,$_POST['license-number'],$_POST['violation-committed'],$_POST['alloted-penalty'],$_POST['settlement-deadline']);
    $file_violation->execute();

    checkSuspensionRevocationDeadlines();
    returnToLicenseActions($serial_number,"Violation registered!");
}

if (isset($_GET['updateLicenseInformation']) && $_GET['updateLicenseInformation'] == true) { // Update license record
    $serial_number = $_GET['serial-number'];
    $sql =
        "UPDATE tblicense
        SET
        name='$_GET[name]',
        sex='$_GET[sex]',
        address='$_GET[address]'
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);

    returnToLicenseActions($serial_number,"Information updated!");
}

if (isset($_POST['deleteLicense']) && $_POST['deleteLicense'] == true) { // Delete license records
    $serial_number = $_POST['serial-number'];
    $license_number = $_POST['license-number'];

    $check = checkUnresolvedViolations($serial_number);
    if ($check == true) {
        returnToLicenseActions($serial_number,"Unresolved violation(s)!");
        return;   
    }

    $sql =
        "DELETE FROM tbviolations WHERE licenseSN='$serial_number';
        DELETE FROM tblogininfo WHERE licenseNumber='$license_number';
        DELETE FROM tblicense WHERE serialNumber='$serial_number'";
    $rs = $conn->multi_query($sql);

    echo "
        <script>
            alert('All records deleted for license with serial number " . $serial_number . "!');
            window.location.href = 'admin-db.php';
        </script>
    ";
}

function viewLicenseViolations($serial_number) {
    $conn = connect();
    $sql =
        "SELECT * FROM tbviolations WHERE licenseSN='$serial_number'";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        echo "
            <tr>
                <td colspan='7' style='text-align: center;'><i>no violations</td>
            </tr>
        ";
    }

    while ($row = $rs->fetch_assoc()) {
        echo "
            <tr>
                <td>" . $row['violationId'] ."</td>
                <td>" . $row['violationCommitted'] ."</td>
                <td>" . $row['penaltyAlloted'] ."</td>
                <td>" . $row['settlementDeadline'] ."</td>
                <td>" . ($row['resolved'] == 0 ? 'No' : 'Yes') ."</td>
                <td>
                    <form action='_functions.php'>
                        <input type='text' name='violation-id' value='" . $row['violationId'] ."' style='display: none;'>
                        <input tpe='text' name='serial-number' value='" . $row['licenseSN'] . "' style='display: none;'>
                        <button class='btn btn-sm btn-success' name='resolve-violation' value='true'>Resolve</button>
                    </form>
                </td>
                <td>
                    <form action='_functions.php'>
                        <input type='text' name='violation-id' value='" . $row['violationId'] ."' style='display: none;'>
                        <input tpe='text' name='serial-number' value='" . $row['licenseSN'] . "' style='display: none;'>
                        <button class='btn btn-sm btn-warning' name='delete-violation-record' value='true'>Delete</button>
                    </form>
                </td>
            </tr>
        ";
    }
}

// Violation actions
if (isset($_GET['resolve-violation']) && $_GET['resolve-violation'] == true) { // Resolve license violation
    $violation_id = $_GET['violation-id'];
    $sql =
        "UPDATE tbviolations
        SET resolved=1
        WHERE violationId='$violation_id'";
    $rs = $conn->query($sql);

    returnToLicenseActions($_GET['serial-number'],"Violation resolved!");
}

if (isset($_GET['delete-violation-record']) && $_GET['delete-violation-record'] == true) { // Delete violation record
    $serial_number = $_GET['serial-number'];
    $violation_id = $_GET['violation-id'];

    $resolved = checkUnresolvedViolationByID($violation_id);
    if ($resolved == false) {
        returnToLicenseActions($serial_number,"Unresolved violation!");
        return;
    }

    $sql =
        "DELETE FROM tbviolations
        WHERE violationId='$violation_id'";
    $rs = $conn->query($sql);

    returnToLicenseActions($serial_number,"Record deleted!");
}

function checkSuspensionRevocationDeadlines() { // Suspend/revoke licenses not resolved on deadlines
    $conn = connect();
    $sql =
        "SELECT * FROM tbviolations ORDER BY settlementDeadline ASC";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        return;
    } else {
        while ($row = $rs->fetch_assoc()) {
            if ($row['settlementDeadline'] == date("Y-m-d") && $row['resolved'] == 0) {
                if ($row['penaltyAlloted'] == 'License Suspension') {
                    $conn->query("UPDATE tblicense SET status='Suspended' WHERE serialNumber='$row[licenseSN]'");
                } else if ($row['penaltyAlloted'] == 'License Revocation') {
                    $conn->query("UPDATE tblicense SET status='Revoked' WHERE serialNumber='$row[licenseSN]'");
                    return;
                }
            } 
        }
    }
}

function checkUnresolvedViolationByID($violation_id) { // Check individual violation status
    $conn = connect();
    $sql =
        "SELECT resolved
        FROM tbviolations
        WHERE violationId='$violation_id'";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $resolved = true;
    } else {
        $row = $rs->fetch_assoc();
        if ($row['resolved'] == 0) $resolved = false;
        else $resolved = true;
    }

    return $resolved;
}

function checkUnresolvedViolations($serial_number) { // Check unresolved violations
    $conn = connect();
    $sql =
        "SELECT resolved
        FROM tbviolations
        WHERE licenseSN='$serial_number'";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $unresolved_violations = false;
    }

    while ($row = $rs->fetch_assoc()) {
        if ($row['resolved'] == 0) {
            $unresolved_violations = true;
        } else $unresolved_violations = false;
    }

    return $unresolved_violations;
}

function viewAllLicense() {
    $conn = connect();
    $sql =
        "SELECT *
        FROM tblicense";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        echo "
            <tr>
                <td colspan='8' style='text-align: center;'><i>no registered license</td>
            </tr>
        ";
    } else {
        while ($row = $rs->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['sex'] . "</td>
                    <td>" . $row['address'] . "</td>
                    <td>" . $row['licenseNumber'] . "</td>
                    <td>" . $row['dateRegistered'] . "</td>
                    <td>" . ($row['dateRenewed'] == null ? 'no record' : $row['dateRenewed'] ) . "</td>
                    <td>" . $row['expirationDate'] . "</td>
                    <td>" . $row['status'] . "</td>
                    <td>
                        <form method='get' action='license-actions.php'>
                            <input type='text' name='serial-number' value='" . $row['serialNumber'] . "' style='display:none;'>
                            <button class='btn btn-primary'>Actions</button>
                        </form>
                    </td>
                </tr>
            ";
        }
    }
}

function getLicenseInfo($serial_number) {
    $conn = connect();
    $sql =
        "SELECT *
        FROM tblicense
        WHERE serialNumber='$serial_number'";
    $rs = $conn->query($sql);
    
    while ($row = $rs->fetch_assoc()) {
        $info[] = $row;
    }

    return $info;
}

function updateLicenseExpired() {
    $conn = connect();
    $sql =
        "UPDATE tblicense
        SET status='Expired'
        WHERE expirationDate='" . date("Y-m-d") ."'";
    $rs = $conn->query($sql);
}

?>