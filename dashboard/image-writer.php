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

include('../includes/_functions.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Image Writer |
        <?php echo _siteconfig('_sitetitle'); ?>
    </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <script src="../assets/plugins/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
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
                    <div class="row">
                        <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                <h4 class="card-title"><img src="../assets/icons/pictures.png" class="menu-icon" style="margin-right: 15px;margin-top:-5px;width:28px;margin-left:5px">Image Writer ( AI Image )</h4>
                                <p class="card-description">
                                Introducing AI Story Writer, a powerful tool that can help you create stories in no time. Create storyboards, novel or short story
                                </p>
                                <hr style="width: 100%;">
                                
                                <div class="row" style="margin-bottom: 20px;">
                                <div class="col-lg-12">
                                    <label>Describe Content (Short Summary)</label><br>
                                    <textarea id="description" style="width:100%;line-height: 1.25; font-size: 15px; margin: 0; padding: 15px;" rows="4" class="form-control"></textarea>
                                </div>
                                <div class="col-lg-12" style="margin-top: 20px;">
                                    <label>Select Size</label><br>
                                    <select id="size" class="form-control">
                                        <option value="large">1024 x 1024</option>
                                        <option value="medium">512 x 512</option>
                                        <option value="small">256 x 256</option>
                                    </select>
                                </div>
                                </div>
                                <div class="row" style="margin-top: 30px;">
                                        <div class="col-lg-12">
                                            <button class="btn btn-primary" style="border-radius: 6px;width:100%" id="submit"><div style="width:20px;height:20px;margin-right:10px" class="spinner-border text-light" id="loader" role="status">
                                                <span class="visually-hidden"></span>
                                            </div> Generate Image</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div id="alert" class="alert alert-success" role="alert">
                                        Successfull <strong id="alerttext"></strong> Balance Consumed
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <img id="content"  style="width:100%;height:60vh">
                                        </div>
                                    </div>
                                </div>
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
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script>
            $('#loader').hide();
            $('#alert').hide();
            document.getElementById("submit").onclick = function getContent(){
                $('#loader').show();
                var description = document.getElementById("description").value
                var size = document.getElementById("size").value
                $.ajax({
                    type: "POST",
                    url: "_getcontent.php",
                    data: 'desc=' + description + '&words=' + size + '&content=image',
                    success: function (data) {
                        console.log(data)
                        document.getElementById("content").src = data;
                        $('#loader').hide();
                        $.ajax({
                            type: "POST",
                            url: "_getsession.php",
                            data: 'session=total_cost',
                            success: function (data) {
                                document.getElementById("alerttext").innerHTML = data;
                                $('#alert').show();
                            }
                        });
                    }
                });
            }
            document.getElementById("words").oninput = function() {
                myFunction()
            };

            function myFunction() {
            var val = document.getElementById("words").value //gets the oninput value
            document.getElementById('output').innerHTML = val //displays this value to the html page
            }

        </script>

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