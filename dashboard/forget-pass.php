<?php 
session_start();

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true){
  echo "<script>";
  echo "window.location.href = 'index'";
  echo "</script>";
}

if(!isset($_SESSION['forgot_success'])){
  $_SESSION['forgot_success'] = false;
}

require('../includes/_functions.php'); 
if(isset($_POST['submit'])){
    $useremail = $_POST['useremail'];
    $userphone = $_POST['userphone'];
    _forgetpass($useremail,$userphone);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Forget Password | <?php echo _siteconfig('_sitetitle'); ?></title>
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
            <?php if($_SESSION['forgot_success']){ ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Password Changed!</strong> We've mailed your new password.
            </div>
            <?php } ?>
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../uploads/images/<?php echo _siteconfig('_sitelogo'); ?>" alt="logo">
              </div>
              <h4>Forgot password?</h4>
              <h6 class="font-weight-light">Recovery is easy. It only takes a few steps</h6>
              <form class="pt-3" method="POST" action="">
                <div class="form-group">
                  <input type="email" name="useremail" required class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email Id">
                </div>
                <div class="form-group">
                  <input type="text" pattern="[1-9]{1}[0-9]{9}" name="userphone" required class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Phone No">
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit">SUBMIT</button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Remember Password? <a href="login" class="text-primary">Login</a>
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
