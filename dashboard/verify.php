<?php 

require('../includes/_functions.php'); 

session_start();

// if(isset($_SESSION['isLoggedIn'])){
//   echo "<script>";
//   echo "window.location.href = 'index'";
//   echo "</script>";
// }

if(isset($_POST['submit'])){
    $userotp = $_POST['userotp'];
    _verifyotp($userotp);
}
if(isset($_POST['otp'])){
  _resendtop();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Verify Account | <?php echo _siteconfig('_sitetitle'); ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
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
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../uploads/images/<?php echo _siteconfig('_sitelogo'); ?>" alt="logo">
              </div>
              <h4>Verify Account</h4>
              <h6 class="font-weight-light">Enter OTP recieved on you mobile number</h6>
              <form class="pt-3" method="POST" action="">
                <div class="form-group">
                  <input type="text" name="userotp" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="OTP">
                </div>
                <button name="otp" style="cursor: pointer;float:right;margin-bottom: 20px; border: none; background-color:white" class="auth-link text-black"><img src="../assets/icons/send.png" style="width: 15px;height: 15px" alt="send">&nbsp;&nbsp;Resend OTP</button>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit">VERIFY ACCOUNT</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
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
  <!-- endinject -->
</body>

</html>
