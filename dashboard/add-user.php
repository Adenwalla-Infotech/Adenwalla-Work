<?php

session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn'] || $_SESSION['isLoggedIn'] == '') {
    echo "<script>";
    echo "window.location.href = 'login'";
    echo "</script>";
} else {
    if ($_SESSION['userVerify'] != 'true') {
        echo "<script>";
        echo "window.location.href = 'verify'";
        echo "</script>";
    }
}

if (isset($_SESSION['forgot_success']) || !isset($_SESSION['forgot_success'])) {
    $_SESSION['forgot_success'] = false;
}

require('../includes/_functions.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $useremail = $_POST['useremail'];
    $usertype = $_POST['usertype'];
    $userphone = $_POST['userphone'];


    $userwebsite =  $_POST['userwebsite'];



    if (isset($_POST['notify'])) {
        $notify = $_POST['notify'];
    } else {
        $notify = false;
    }
    if (isset($_POST['isactive'])) {
        $isactive = $_POST['isactive'];
    } else {
        $isactive = false;
    }
    if (isset($_POST['isverified'])) {
        $isverified = $_POST['isverified'];
    } else {
        $isverified = false;
    }

    _createuser(  $username , $useremail, $usertype, $userphone, $userwebsite,  $isactive, $isverified, $notify);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add User | <?php echo _siteconfig('_sitetitle'); ?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <?php include('templates/_header.php'); ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <?php include('templates/_sidebar.php'); ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php if ($_SESSION['forgot_success']) { ?>
                        <div id="liveAlertPlaceholder">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>User Created!</strong> New user created successfully.
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create a New User Account</h4>
                                <p class="card-description">
                                    When you create a new user account, you must assign access credentials, a user type, and a security password to the user. User type define what actions the user has permission to perform. Security password secures users permission to access. You can create multiple user accounts that include administrative right
                                </p>
                                <form method="POST" action="">
                                    <div class="row g-3">
                                        <div class="col">
                                            <label for="username" class="form-label">Name</label>
                                            <input type="text" class="form-control" placeholder="User name" aria-label="user name" id="username" name="username" required>
                                        </div>
                                        <div class="col">
                                            <label for="useremail" class="form-label">Email</label>
                                            <input type="text" class="form-control" placeholder="Email ID" aria-label="Email Id" id="useremail" name="useremail" required>
                                        </div>
                                    </div>
                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col">
                                            <label for="usertype" class="form-label">Account Type</label>
                                            <select style="height: 46px;" id="usertype" name="usertype" class="form-control form-control-lg" id="exampleFormControlSelect2" required>
                                                <option>Account Type</option>
                                                <option value="0">Student</option>
                                                <option value="1">Teacher</option>
                                            </select>
                                        </div>

                                        <div class="col">
                                            <label for="userphone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" placeholder="Phone Number" aria-label="phone" id="userphone" name="userphone" required>
                                        </div>
                                    </div>
                                    <div class="row g-3" style="margin-top: 20px;">


                                        <div class="col">
                                            <label for="userlocation" class="form-label">Location</label>
                                            <input type="text" class="form-control" placeholder="IP Location" aria-label="user location" id="userlocation" name="userlocation">
                                        </div>


                                        <div class="col">
                                            <label for="userwebsite" class="form-label">Website</label>
                                            <input type="text" class="form-control" placeholder="User Website" aria-label="user website" id="userwebsite" name="userwebsite">
                                        </div>

                                    </div>

                                    <div class="row g-3" style="margin-top: 30px;">

                                        <div class="col" style="margin-top: 10px;">
                                            <label class="checkbox-inline" style="margin-left: 20px;">
                                                <input name="isactive" value="true" type="checkbox"> &nbsp; Is Active
                                            </label>
                                            <label class="checkbox-inline" style="margin-left: 20px;">
                                                <input name="isverified" value="true" type="checkbox"> &nbsp; Is Verified
                                            </label>
                                            <label class="checkbox-inline" style="margin-left: 20px;">
                                                <input name="notify" value="true" type="checkbox"> &nbsp; Notify User (SMS/Email)
                                            </label>
                                        </div>


                                    </div>

                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 150px;margin-left: -10px" class="btn btn-primary">Create Account</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <?php include('templates/_footer.php'); ?>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <div class="container"></div>
</body>
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</html>