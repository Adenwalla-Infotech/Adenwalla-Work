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

if(isset($_SESSION['forgot_success']) || !isset($_SESSION['forgot_success'])){
    $_SESSION['forgot_success'] = false;
  }

require('../includes/_functions.php'); 

if(isset($_POST['submit'])){
    $suppliername = $_POST['suppliername'];
    $apikey = $_POST['apikey'];
    $baseurl = $_POST['baseurl'];
    if(isset($_POST['isactive'] ) ){
        $isactive = $_POST['isactive'];
    }else{
        $isactive = false;
    }
    _saveaiconfig($suppliername,$apikey,$baseurl,$isactive);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>AI Config | <?php echo _siteconfig('_sitetitle'); ?></title>
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
        <?php if($_SESSION['forgot_success']){ ?>
            <div id="liveAlertPlaceholder">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Message Sent!</strong> message sent successfully.
                </div>
            </div>
            <?php } ?>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title">AI Configuration (OpenAI)</h4>
                  <p class="card-description">
                  The OpenAI API uses API keys for authentication. Visit your API Keys page to retrieve the API key you'll use in your requests.
                  Remember that your API key is a secret! Do not share it with others or expose it in any client-side code (browsers, apps).
                  </p>
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Supplier name" aria-label="user name" value="<?php echo _aiconfig('_suppliername'); ?>" name="suppliername" required>
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" placeholder="API Key" aria-label="api key" value="<?php echo _aiconfig('_apikey'); ?>" name="apikey" required>
                            </div>
                        </div>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col">
                                <input type="text" value="<?php echo _aiconfig('_baseurl'); ?>" class="form-control" placeholder="Base URL" aria-label="base url" name="baseurl" required>
                            </div>
                            <div class="col" style="margin-top: 10px;">
                            <label class="checkbox-inline" style="margin-left: 20px;">
                                    <?php 
                                        if( _aiconfig('_supplierstatus')==true){?><input name="isactive" value="true" checked type="checkbox">&nbsp;Is Active<?php }
                                        if( _aiconfig('_supplierstatus')!=true){?><input name="isactive" value="true" type="checkbox">&nbsp;Is Active<?php }
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col">
                                <label for="formFile" class="form-label">Basic Engine</label>
                                <input type="text" value="<?php echo _aiconfig('text-ada-001'); ?>" class="form-control" placeholder="Base URL" aria-label="base url" name="baseurl" required>
                            </div>
                            <div class="col">
                                <label for="formFile" class="form-label">Elementry Engine</label>
                                <input type="text" value="<?php echo _aiconfig('text-babbage-001'); ?>" class="form-control" placeholder="Base URL" aria-label="base url" name="baseurl" required>
                            </div>
                            <div class="col">
                                <label for="formFile" class="form-label">Premium Engine</label>
                                <input type="text" value="<?php echo _aiconfig('text-curie-001'); ?>" class="form-control" placeholder="Base URL" aria-label="base url" name="baseurl" required>
                            </div>
                            <div class="col">
                                <label for="formFile" class="form-label">Enterprice Engine</label>
                                <input type="text" value="<?php echo _aiconfig('text-davinci-002'); ?>" class="form-control" placeholder="Base URL" aria-label="base url" name="baseurl" required>
                            </div>
                        </div>
                        <div class="col-12" style="margin-top: 30px;">
                            <button type="submit" name="submit" style="width: 180px;margin-left: -10px" class="btn btn-primary"><i class="mdi mdi-content-save"></i>&nbsp;&nbsp;Save Settings</button>
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