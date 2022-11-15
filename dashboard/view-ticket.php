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

require('../includes/_functions.php'); 

$_ticid = $_GET['id'];


if(isset($_POST['submit'])){
    $message = $_POST['message'];
    $email = $_SESSION['userEmailId'];
    $image = null;
    if($_FILES["file"]["name"] != ''){
        $file=$_FILES["file"]["name"];
        $extension = substr($file,strlen($file)-4,strlen($file));
        $image=md5($file).$extension;
        move_uploaded_file($_FILES["file"]["tmp_name"],"../uploads/tickets/".$image);
    }
 
    _saveticketres($_ticid,$message,$image,$email);
}

if(isset($_POST['statustype'])){
    $status = $_POST['statustype'];
    _updateticket('_status',$status,$_ticid);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View <?php echo _getsinglticket($_ticid,'_title'); ?> | <?php echo _siteconfig('_sitetitle'); ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <script src="../assets/plugins/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
      tinymce.init({
        selector: '#mytextarea',
        height : 300,
      });
    </script>
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<style>
    .tox-tinymce {
    border: none;
    border-radius: 0px;
    box-shadow: none;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
    overflow: hidden;
    position: relative;
    visibility: inherit!important;
}
</style>
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
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="card-title"><?php echo _getsinglticket($_ticid,'_title'); ?></h4>
                        </div>
                        <div class="col-lg-4">
                            <form action="" method="post">
                                <select style="height: 36px;" name="statustype" class="form-control form-control-lg"  id="exampleFormControlSelect2"  onchange="this.form.submit()" required>
                                    <?php 
                                        $type = _getsinglticket($_ticid,'_status');
                                        echo $type;
                                        

                                        if($type=='open'){?><option value="open" selected>Open</option><?php }
                                        if($type=='pending'){?><?php }
                                        if($type=='resolved'){?><option value="resolved" selected>Resolved</option><?php }
                                        if($type=='closed'){?><option value="closed" selected>Closed</option><?php }
                                        if($type!='open'){?><option value="open">Open</option><?php }
                                        if($type!='pending'){?><option value="pending">Pending</option><?php }
                                        if($type!='resolved'){?><option value="resolved">Resolved</option><?php }
                                        if($type!='closed'){?><option value="closed">Closed</option><?php }
                                    ?>
                                </select>
                            </form>
                        </div>
                    </div>
                  <hr>
                  <p class="card-description">
                    <?php echo _getsinglticket($_ticid,'_message'); ?>
                  </p>
                  <hr>
                    <div class="row">
                        <div class="col-lg-3" style="margin-bottom: 5px;">
                            <i style="font-size: 18px" class="mdi mdi-calendar text-lg text-primary"></i>&nbsp;&nbsp;<?php echo date("F j, Y", strtotime(_getsinglticket($_ticid,'CreationDate'))); ?>
                        </div>
                        <div class="col-lg-3" style="margin-bottom: 5px;">
                            <i style="font-size: 18px" class="mdi mdi-account-circle text-primary"></i>&nbsp;&nbsp;<span style="font-size: 14px"> <?php echo _getsinglticket($_ticid,'_useremail'); ?></span>
                        </div>
                        <div class="col-lg-3">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i style="font-size: 18px" class="mdi mdi-reply text-primary"></i>&nbsp;&nbsp;Leave Reply
                        </a>
                        </div>
                        <?php if(_getsinglticket($_ticid,'_image') != ''){ ?>
                        <div class="col-lg-3">
                            <a href="../uploads/tickets/<?php echo _getsinglticket($_ticid,'_image'); ?>"><i style="font-size: 18px" class="mdi mdi-cloud-download text-primary"></i>&nbsp;&nbsp;Download Attachment</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <?php echo _getticketres($_ticid); ?>
                </ul>    
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <textarea  name="message" rows="2" id="mytextarea">Hello, World!</textarea>
                        <input class="form-control" type="file" name="file">
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" name="submit" class="btn btn-primary">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <script src="../assets/js/todolist.js"></script>
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</html>