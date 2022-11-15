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

    $membershipname = $_POST['membershipname'];
    $membershipdesc = $_POST['membershipdesc'];


    if (isset($_POST['isactive'])) {
        $isactive = $_POST['isactive'];
    } else {
        $isactive = false;
    }

    _createMembership($membershipname, $membershipdesc,$isactive);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Category | <?php echo _siteconfig('_sitetitle'); ?></title>
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
                                <strong>Membership Created!</strong> New Membership created successfully.
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create Membership</h4>
                                <p class="card-description">
                                    Before you start writing about your new topic, it's important to do some research. This will help you to understand the topic better, This will make it easier for you to write about the topic, and it will also make it more likely that people will be interested in reading what you have to say.
                                </p>
                                <form method="POST" action="">

                                    <div class="row g-3">
                                        <div class="col">
                                            <label for="membershipname" class="form-label">Membership Name</label>
                                            <input type="text" class="form-control" placeholder="Membership name" aria-label="Membership name" id="membershipname" name="membershipname" required>
                                        </div>
                                        <div class="col">
                                            <label for="membershipdesc" class="form-label">Membership Description</label>
                                            <input type="text" class="form-control" placeholder="Membership Description" aria-label="Membership Description" id="membershipdesc" name="membershipdesc" required>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 10px;">

                                        <div class="col" style="margin-top: 10px;">
                                            <label class="checkbox-inline" style="margin-left: 5px;">
                                                <input name="isactive" value="true" type="checkbox"> &nbsp; Is Active
                                            </label>
                                        </div>

                                    </div>


                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 200px;margin-left: -10px" class="btn btn-primary">Create Membership</button>
                                        
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</html>

