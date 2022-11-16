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

    $couponname = $_POST['couponname'];
    $couponamount = $_POST['couponamount'];
    $useremail = $_POST['useremail'];


    _updateCouponTranscation($_id, $couponname, $couponamount, $useremail);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Transaction No : <?php echo _getSingleCouponTranscations($_id, '_id') ?> | <?php echo _siteconfig('_sitetitle'); ?></title>
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
                                <h4 class="card-title">Edit Coupon Transcations</h4>
                                <p class="card-description">
                                    When you edit user account, you must assign access credentials, a user type, and a security password to the user. User type define what actions the user has permission to perform. Security password secures users permission to access. You can create multiple user accounts that include administrative right
                                </p>

                                <form method="POST" action="" class="needs-validation" novalidate>
                                    <div class="row g-3">
                                        <div class="col">
                                            <label for="couponname" class="form-label">Coupon Name</label>
                                            <input type="text" value="<?php echo _getSingleCouponTranscations($_id, '_couponname'); ?>" class="form-control" placeholder="Coupon Name" aria-label="Coupon Name" id="couponname" name="couponname" required>
                                            <div class="invalid-feedback">Please type correct couponname</div>
                                        </div>
                                        <div class="col">
                                            <label for="couponamount" class="form-label">Coupon Amount</label>
                                            <input type="text" value="<?php echo _getSingleCouponTranscations($_id, '_couponamount'); ?>" class="form-control" placeholder="Coupon Amount" aria-label="Coupon Amount" name="couponamount" required>
                                            <div class="invalid-feedback">Please type correct amount</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">


                                        <div class="col">
                                            <label for="useremail" class="form-label">User Email</label>
                                            <input type="text" class="form-control" value="<?php echo _getSingleCouponTranscations($_id, '_useremail'); ?>" placeholder="User Email" aria-label="User Email" id="useremail" name="useremail" required>
                                            <div class="invalid-feedback">Please type correct user email</div>
                                            
                                        </div>


                                    </div>



                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 250px;margin-left: -10px" class="btn btn-primary">Update Coupon Transaction</button>
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
        <script src="../includes/_validation.js"></script>

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