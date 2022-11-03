<?php

session_start();

if(!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn'] || $_SESSION['isLoggedIn'] == ''){
    echo "<script>";
    echo "window.location.href = 'login'";
    echo "</script>";
}else{
    if($_SESSION['userVerify'] != 'true'){
        echo "<script>";
        echo "window.location.href = 'verify'";
        echo "</script>";
    }
}

if(isset($_SESSION['send_mail']) || !isset($_SESSION['send_mail'])){
    $_SESSION['send_mail'] = false;
  }

require('../includes/_functions.php'); 

if(isset($_POST['submit'])){
    $hostname = $_POST['hostname'];
    $hostport = $_POST['hostport'];
    $emailid = $_POST['email'];
    $password = $_POST['password'];
    $sendername = $_POST['sender'];
    if(isset($_POST['smtpauth'] ) ){
        $smtpauth = $_POST['smtpauth'];
    }else{
        $smtpauth = false;
    }
    if(isset($_POST['status'] ) ){
        $status = $_POST['status'];
    }else{
        $status = false;
    }
    _saveemailconfig($hostname,$hostport,$smtpauth,$emailid,$password,$sendername,$status);
}
if(isset($_POST['send'])){
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    _notifyuser($email,'',$message,$subject);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Email Config | <?php echo _siteconfig('_sitetitle'); ?></title>
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
        <?php if($_SESSION['send_mail']){ ?>
            <div id="liveAlertPlaceholder">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Mail Sent!</strong> mail sent successfully.
                </div>
            </div>
            <?php } ?>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Email Configuration (SMTP)</h4>
                  <p class="card-description">
                    If you have set up an E-Mail address in the Control Panel, Log in to your panel account. Navigate to Email > Email Account > Checkmail. you must have recieved a mial with configuration details, paste the credentials over here, Kindly fo not make any changes over here if you have no knowlddge of SMTP contact support in case of any confusion.
                  </p>
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Host name" aria-label="user name" value="<?php echo _emailconfig('_hostname'); ?>" name="hostname" required>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Host port" aria-label="api key" value="<?php echo _emailconfig('_hostport'); ?>" name="hostport" required>
                            </div>
                        </div>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col">
                                <input type="email" value="<?php echo _emailconfig('_emailaddress'); ?>" class="form-control" placeholder="Email Id" aria-label="email" name="email" required>
                            </div>
                            <div class="col">
                                <input type="password" value="<?php echo _emailconfig('_emailpassword'); ?>" class="form-control" placeholder="Email Password" aria-label="password" name="password" required>
                            </div>
                        </div>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col">
                                <input type="text" value="<?php echo _emailconfig('_sendername'); ?>" class="form-control" placeholder="Sender name" aria-label="sender" name="sender" required>
                            </div>
                            <div class="col" style="margin-top: 10px;">
                                <label class="checkbox-inline" style="margin-left: 20px;">
                                    <?php 
                                        if( _emailconfig('_smtpauth')==true){?><input name="smtpauth" value="true" checked type="checkbox">&nbsp;SMTP Auth<?php }
                                        if( _emailconfig('_smtpauth')!=true){?><input name="smtpauth" value="true" type="checkbox">&nbsp;SMTP Auth<?php }
                                    ?>
                                </label>
                                <label class="checkbox-inline" style="margin-left: 40px;">
                                    <?php 
                                        if( _emailconfig('_supplierstatus')==true){?><input name="status" value="true" checked type="checkbox">&nbsp;Is Active<?php }
                                        if( _emailconfig('_supplierstatus')!=true){?><input name="status" value="true" type="checkbox">&nbsp;Is Active<?php }
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-12" style="margin-top: 30px;">
                            <button type="submit" name="submit" style="width: 150px;margin-left: -10px" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>    
            </div>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card" style="margin-top: 10px;">
                <div class="card-body">
                    <h4 class="card-title">Service Delivery Testing (Email SMTP)</h4>
                    <p class="card-description">
                        Log in to your Fast2SMS account. Navigate to Settings > All settings > API Keys. If you have previously created an API key, paste the credentials over here, Kindly fo not make any changes over here if you have no knowlddge of API contact support in case of any confusion.
                    </p>
                    <form action="" method="post">
                        <div class="row g-3">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Email Id" aria-label="user name" name="email" required>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Subject" aria-label="user name" name="subject" required>
                            </div>
                        </div>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col">
                                <textarea type="text" class="form-control" placeholder="Message" aria-label="api key" name="message" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="margin-top: 30px;">
                            <button type="submit" name="send" style="width: 150px;margin-left: -10px" class="btn btn-primary">Send Email</button>
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