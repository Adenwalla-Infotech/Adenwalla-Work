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
    $subject = $_POST['subject'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $message = $_POST['message'];
    $useremail = $_SESSION['userEmailId'];

    if($_FILES["file"]["name"] != ''){
        $file=$_FILES["file"]["name"];
        $extension = substr($file,strlen($file)-4,strlen($file));
        $newfile=md5($file).$extension;
        move_uploaded_file($_FILES["file"]["tmp_name"],"../uploads/tickets/".$newfile);
    }else{
        $newfile = null;
    }

    _saveticket($subject,$category,$status,$newfile,$useremail,$message);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Add Ticket | <?php echo _siteconfig('_sitetitle'); ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <script src="../assets/plugins/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
      tinymce.init({
        selector: '#mytextarea'
      });
    </script>
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
                <strong>User Created!</strong> New user created successfully.
                </div>
            </div>
            <?php } ?>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create Ticket (Support Ticket)</h4>
                  <p class="card-description">
                     If you can't find a solution to your problems in our knowledgebase, you can submit a ticket by selecting the appropriate department below & subject below. Tickets can also be created by simply sending an email. Ticket responses can also be created by replying to the same email.
                  </p>
                    <form method="POST" action="" enctype="multipart/form-data"  class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-lg-6" style="margin-bottom: 20px;">
                                <label for="formFile" class="form-label">Tickect Subject</label>
                                <input type="text" class="form-control" placeholder="Ticket Subject" aria-label="user name" name="subject" required>
                                <div class="invalid-feedback">Please enter ticket subject correctly</div>
                            </div>
                            <div class="col-lg-6" style="margin-bottom: 20px;">
                                <label for="formFile" class="form-label">Select Category</label>
                                <select style="height: 46px;" name="category" class="form-control form-control-lg" id="exampleFormControlSelect2" required>
                                    <option selected disabled value="" >Ticket Category</option>
                                    <option value="0">Help & Support</option>
                                    <option value="1">Sales & Service</option>
                                </select>
                                <div class="invalid-feedback">Please select ticket category correctly</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6" style="margin-bottom: 20px;">
                                <label for="formFile" class="form-label">Select Status</label>
                                <select style="height: 46px;" name="status" class="form-control form-control-lg" id="exampleFormControlSelect2" required>
                                    <option selected disabled value="" >Ticket Status</option>
                                    <option value="open">Open</option>
                                    <option value="pending">Pending</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <div class="invalid-feedback">Please select ticket status correctly</div>
                            </div>
                            <div class="col-lg-6" style="margin-bottom: 20px;">
                                <label for="formFile" class="form-label">Attach File (Optional)</label>
                                <input class="form-control" name="file" type="file" id="formFile">
                                <div class="invalid-feedback">Please upload file correctly</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col">
                                <textarea name="message" id="mytextarea">Hello, World!</textarea>
                                <div class="invalid-feedback">Please enter  description</div>
                            </div>
                        </div>
                        <div class="col-12" style="margin-top: 30px;">
                            <button type="submit" name="submit" style="width: 150px;margin-left: -10px" class="btn btn-primary">Create Ticket</button>
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