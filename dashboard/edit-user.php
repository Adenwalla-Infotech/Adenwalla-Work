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

require('../includes/_functions.php');

$_id = $_GET['id'];


if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $useremail = $_POST['useremail'];
    $usertype = $_POST['usertype'];
    $userpassword = $_POST['userpassword'];
    $userphone = $_POST['userphone'];


    $userlongitude = $_POST['userlongitude'];
    $userlatitude = $_POST['userlatitude'];

    $userbio = $_POST['userbio'];
    $userage = $_POST['userage'];

    $userwebsite = $_POST['userwebsite'];
    $usertwitter = $_POST['usertwitter'];
    $userinstagram = $_POST['userinstagram'];
    $userlinkedin = $_POST['userlinkedin'];



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

    _updateuser($userpassword, $useremail, $username, $usertype, $userphone, $userlongitude, $userlatitude, $userbio, $userage, $userwebsite, $usertwitter, $userinstagram, $userlinkedin, $isactive, $isverified, $_id);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit <?php echo _getsingleuser($_id, '_username'); ?> | <?php echo _siteconfig('_sitetitle'); ?></title>
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
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit User Account</h4>
                                <p class="card-description">
                                    When you edit user account, you must assign access credentials, a user type, and a security password to the user. User type define what actions the user has permission to perform. Security password secures users permission to access. You can create multiple user accounts that include administrative right
                                </p>
                                <form method="POST" action="">
                                    <div class="row g-3">
                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_username'); ?>" class="form-control" placeholder="User name" aria-label="user name" name="username" required>
                                        </div>
                                        <div class="col">
                                            <input type="email" value="<?php echo _getsingleuser($_id, '_useremail'); ?>" class="form-control" placeholder="Email ID" aria-label="Email Id" name="useremail" required>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col">
                                            <select style="height: 46px;" name="usertype" class="form-control form-control-lg" id="exampleFormControlSelect2" required>
                                                <?php
                                                $type = _getsingleuser($_id, '_usertype');
                                                echo $type;
                                                if ($type == 0) { ?><option value="0" selected>Student</option><?php }
                                                                                                            if ($type == 1) { ?><option value="1" selected>Teacher</option><?php }
                                                                                                                                                                        if ($type == 2) { ?><option value="2" selected>Site Admin</option><?php }
                                                                                                                                                                                                                                        if ($type != 0) { ?><option value="0">Student</option><?php }
                                                                                                                                                                                                                                                                                            if ($type != 1) { ?><option value="1">Teacher</option><?php }
                                                                                                                                                                                                                                                                                                                                                if ($type != 2) { ?><option value="2">Site Admin</option><?php }
                                                                                                                                                                                                                                                                                                                                                                                            ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input disabled type="password" value="<?php echo _getsingleuser($_id, '_userpassword'); ?>" class="form-control" placeholder="Password" aria-label="password" name="userpassword" name="Password" required>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col">
                                            <input type="tel" value="<?php echo _getsingleuser($_id, '_userphone'); ?>" class="form-control" placeholder="Phone Number" aria-label="phone" name="userphone" required>
                                        </div>

                                        <div class="col">
                                            <!-- <input type="text" class="form-control" placeholder="User Bio" aria-label="User Bio" name="userbio" required> -->
                                            <textarea name="userbio" id="userbio" class="form-control" placeholder="User Bio">
                                            <?php echo _getsingleuser($_id, '_userbio'); ?>
                                            </textarea>
                                        </div>

                                    </div>


                                    <div class="row g-3 " style="margin-top: 20px;">
                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userage'); ?>" class="form-control" placeholder="User Age" aria-label="user age" name="userage">
                                        </div>
                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_usertwitter'); ?>" class="form-control" placeholder="User Twitter" aria-label="user twitter" name="usertwitter">
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userlinked'); ?>" class="form-control" placeholder="User Linkedin" aria-label="user linkedin" name="userlinkedin">
                                        </div>
                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userinstagram'); ?>" class="form-control" placeholder="User Instagram" aria-label="user instagram" name="userinstagram">
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userlatitude'); ?>" class="form-control" placeholder="User Latitude" aria-label="user latitude" name="userlatitude">
                                        </div>
                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userlongitude'); ?>" class="form-control" placeholder="User Longitude" aria-label="user longitude" name="userlongitude">
                                        </div>
                                    </div>


                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col" style="margin-top: 10px;">
                                            <label class="checkbox-inline" style="margin-left: 20px;">
                                                <?php
                                                if (_getsingleuser($_id, '_userstatus') == true) { ?><input name="isactive" value="true" checked type="checkbox">&nbsp;Is Active<?php }
                                                                                                                                                                            if (_getsingleuser($_id, '_userstatus') != true) { ?><input name="isactive" value="true" type="checkbox">&nbsp;Is Active<?php }
                                                                                                                                                                                                                                                                                                    ?>
                                            </label>
                                            <label class="checkbox-inline" style="margin-left: 20px;">
                                                <?php
                                                if (_getsingleuser($_id, '_userverify') == true) { ?><input name="isverified" value="true" checked type="checkbox">&nbsp;Is Verified<?php }
                                                                                                                                                                                if (_getsingleuser($_id, '_userverify') != true) { ?><input name="isverified" value="true" type="checkbox">&nbsp;Is Verified<?php }
                                                                                                                                                                                                                                                                                                            ?>
                                            </label>
                                        </div>

                                        <div class="col">
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_usersite'); ?>" class="form-control" placeholder="User Website" aria-label="user website" name="userwebsite">
                                        </div>

                                    </div>

                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 150px;margin-left: -10px" class="btn btn-primary">Update Details</button>
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