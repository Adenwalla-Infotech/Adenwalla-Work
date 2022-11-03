<?php 

require('includes/_functions.php'); 
if(isset($_POST['submit'])){
    $dbhost = $_POST['dbhost'];
    $dbname = $_POST['dbname'];
    $dbpass = $_POST['dbpassword'];
    $dbuser = $_POST['dbuser'];
    $siteurl = $_POST['siteurl'];
    $username = $_POST['username'];
    $userpassword = $_POST['userpassword'];
    $useremail = $_POST['useremail'];

    if($dbhost && $dbname && $dbuser && $siteurl && $username && $userpassword && $useremail != ''){
       _install($dbhost, $dbname, $dbpass, $dbuser, $siteurl, $username, $userpassword, $useremail);
    }else{
        $alert = new PHPAlert();
        
        $alert->warn("All Feilds are Required");
    }

}

?>
<!DOCTYPE html> 
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash Admin-123</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="assets/images/logo.png" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Complete the installation to continue.</h6>
              <form class="pt-3" method="POST" action="">
                <div class="form-group">
                  <input type="text" name="dbhost" class="form-control form-control-lg" id="exampleInputText1" placeholder="Database Host">
                </div>
                <div class="form-group">
                  <input type="text" name="dbname" class="form-control form-control-lg" id="exampleInputText2" placeholder="Database Name">
                </div>
                <div class="form-group">
                  <input type="text" name="dbuser" class="form-control form-control-lg" id="exampleInputText3" placeholder="Database User">
                </div>
                <div class="form-group">
                  <input type="text" name="dbpassword" class="form-control form-control-lg" id="exampleInputText4" placeholder="Database Password">
                </div>
                <div class="form-group">
                  <input type="text" name="siteurl" class="form-control form-control-lg" id="exampleInputText6" placeholder="Site URL">
                </div>
                <div class="form-group">
                  <input type="text" name="username" class="form-control form-control-lg" id="exampleInputText5" placeholder="Full Name">
                </div>
                <div class="form-group">
                  <input type="email" name="useremail" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email ID">
                </div>
                <div class="form-group">
                  <input type="password" name="userpassword" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit">INSTALL</button>
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
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>
