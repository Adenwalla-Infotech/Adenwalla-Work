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
    $userpassword = $_POST['userpassword'];
    $userwebsite =  $_POST['userwebsite'];
    $userage =  $_POST['userage'];
    $userbio =  $_POST['userbio'];
    $userinstagram =  $_POST['userinstagram'];
    $userlinkedln =  $_POST['userlinkedln'];
    $usertwitter =  $_POST['usertwitter'];

    $getEmail = _getsingleuser($_id, '_useremail');

    if ($getEmail) {
        $useremail = $getEmail;
    } else {
        $useremail = $_POST['useremail2'];
    }



    $getPhone = _getsingleuser($_id, '_userphone');

    if ($getPhone) {
        $userphone = $getPhone;
    } else {
        $userphone = $_POST['userphone2'];
    }



    $userDp = "";


    _updateProfile(
        $_id,
        $username,
        $useremail,
        $userpassword,
        $userphone,
        $userwebsite,
        $userage,
        $userbio,
        $userinstagram,
        $userlinkedln,
        $usertwitter,
        $userDp
    );
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

    <style>
        .imgDiv {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
        }

        .imgDiv img {
            width: 100px;
            border-radius: 50%;
            cursor: pointer;
            opacity: 1;
            transition: 0.5s;
            border: 3px solid rgba(0, 0, 0, 0.2);
        }

        .imgDiv label {
            position: absolute;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            top: 50%;
            left: 44.8%;
            transform: translateY(-50%);
            font-size: 22px;
            background: #fff;
            color: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            z-index: 10;
            transition: 0.5s;
            margin: 0;
            cursor: pointer;
        }
    </style>
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
                                <h4 class="card-title">Edit Profile</h4>
                                <p class="card-description">
                                    When you edit user account, you must assign access credentials, a user type, and a security password to the user. User type define what actions the user has permission to perform. Security password secures users permission to access. You can create multiple user accounts that include administrative right
                                </p>
                                <form method="POST" action="">


                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="imgDiv">
                                            <label for="userdp"> + </label>
                                            <?php

                                            $userDp = _getsingleuser($_id, '_userdp');

                                            if ($userDp) {
                                            ?>
                                            <?php

                                            } else {
                                            ?>
                                                <img src="../assets/images/user.png" alt="">
                                            <?php

                                            }


                                            ?>
                                            <input style="display: none;" name="userdp" type="file" id="userdp">
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col">
                                            <label for="username" class="form-label">Name</label>
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_username'); ?>" class="form-control" placeholder="User name" aria-label="user name" id="username" name="username">
                                        </div>

                                        <?php

                                        $useremail = _getsingleuser($_id, '_useremail');

                                        if ($useremail) {
                                        ?>
                                            <div class="col">
                                                <label for="useremail" class="form-label">Email</label>
                                                <input type="email" value="<?php echo _getsingleuser($_id, '_useremail'); ?>" class="form-control" placeholder="Email ID" aria-label="Email Id" name="useremail1" disabled>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="col">
                                                <label for="useremail" class="form-label">Email</label>
                                                <input type="email" value="<?php echo _getsingleuser($_id, '_useremail'); ?>" class="form-control" placeholder="Email ID" aria-label="Email Id" name="useremail2">
                                            </div>
                                        <?php
                                        }

                                        ?>


                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <?php

                                        $userPhone = _getsingleuser($_id, '_userphone');
                                        if ($userPhone) {
                                        ?>
                                            <div class="col">
                                                <label for="userphone" class="form-label">Phone</label>
                                                <input type="tel" value="<?php echo _getsingleuser($_id, '_userphone'); ?>" class="form-control" placeholder="Phone Number" aria-label="phone" name="userphone1" disabled>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="col">
                                                <label for="userphone" class="form-label">Phone</label>
                                                <input type="tel" value="<?php echo _getsingleuser($_id, '_userphone'); ?>" class="form-control" placeholder="Phone Number" aria-label="phone" name="userphone2">
                                            </div>
                                        <?php
                                        }

                                        ?>



                                        <div class="col">
                                            <label for="userpassword" class="form-label">Password</label>
                                            <input type="password" value="<?php echo _getsingleuser($_id, '_userpassword'); ?>" class="form-control" placeholder="Password" aria-label="userpassword" name="userpassword">
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="userage" class="form-label">Age</label>
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userage'); ?>" class="form-control" placeholder="User Age" aria-label="user age" name="userage">
                                        </div>

                                        <div class="col">
                                            <label for="userbio" class="form-label">Bio</label>
                                            <textarea class="form-control" placeholder="Tell us about yourself" aria-label="user bio" name="userbio">
                                            <?php echo _getsingleuser($_id, '_userbio'); ?>
                                            </textarea>
                                        </div>

                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="userwebsite" class="form-label">Website</label>
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_usersite'); ?>" class="form-control" placeholder="User Website" aria-label="user website" name="userwebsite">
                                        </div>

                                        <div class="col">
                                            <label for="userinstagram" class="form-label">Instagram</label>
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userinstagram'); ?>" class="form-control" placeholder="User Instagram" aria-label="user instagram" name="userinstagram">
                                        </div>

                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="userlinkedln" class="form-label">Linkedln</label>
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_userlinked'); ?>" class="form-control" placeholder="User Linkedln" aria-label="user linkedln" name="userlinkedln">
                                        </div>

                                        <div class="col">
                                            <label for="usertwitter" class="form-label">Twitter</label>
                                            <input type="text" value="<?php echo _getsingleuser($_id, '_usertwitter'); ?>" class="form-control" placeholder="User Twitter" aria-label="user twitter" name="usertwitter">
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