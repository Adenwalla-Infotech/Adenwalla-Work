<?php 
session_start();

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true){
  echo "<script>";
  echo "window.location.href = 'index'";
  echo "</script>";
}

if(!isset($_SESSION['signup_success'])){
  $_SESSION['signup_success'] = false;
}

require('../includes/_functions.php'); 
if(isset($_POST['submit'])){
    $userpassword = $_POST['userpassword'];
    $useremail = $_POST['useremail'];
    _login($userpassword, $useremail);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login | <?php echo _siteconfig('_sitetitle'); ?></title>
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
            <?php if($_SESSION['signup_success']){ ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Signup Success!</strong> kindly login to access dashboard.
            </div>
            <?php } ?>
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../uploads/images/<?php echo _siteconfig('_sitelogo'); ?>" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3 needs-validation" method="POST" action=""   novalidate>
                <div class="form-group">
                  <input type="text" name="useremail" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email or Phone No" required>
                  <div class="invalid-feedback">Please type correct email</div>
                </div>
                <div class="form-group">
                  <input type="password" name="userpassword" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" required>
                  <div class="invalid-feedback">Please type correct password</div>
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit">SIGN IN</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="forget-pass" class="auth-link text-black">Forgot password?</a>
                </div>

                <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="signup" class="text-primary">Create</a>
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

  <script src="../includes/_validation.js"></script>


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
