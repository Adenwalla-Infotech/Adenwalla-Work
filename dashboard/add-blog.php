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

if (isset($_SESSION['blog_success']) || !isset($_SESSION['blog_success'])) {
    $_SESSION['blog_success'] = false;
}

if (isset($_SESSION['blog_error']) || !isset($_SESSION['blog_error'])) {
    $_SESSION['blog_error'] = false;
}
require('../includes/_functions.php');

if (isset($_POST['submit'])) {

    $_blogtitle = $_POST['_blogtitle'];
    $_blogdesc = $_POST['_blogdesc'];
    $_blogcategory = $_POST['categoryId'];
    $_blogsubcategory = $_POST['subcategoryId'];
    $_blogmetadesc = $_POST['_blogmetadesc'];

    if (isset($_POST['_status'])) {
        $_status = 'true';
    } else {
        $_status = false;
    }

    $_userid = $_SESSION['userId'];


    if ($_FILES["file"]["name"] != '') {
        $file = $_FILES["file"]["name"];
        $extension = substr($file, strlen($file) - 4, strlen($file));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $_blogimg = md5($file) . $extension;
            move_uploaded_file($_FILES["file"]["tmp_name"], "../uploads/blogsPics/" . $_blogimg);
        }
    }



    _createBlog($_blogtitle, $_blogdesc, $_blogcategory, $_blogsubcategory, $_blogmetadesc, $_blogimg, $_userid, $_status);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Blog |
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
                    <?php

                    if ($_SESSION['blog_success']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Blog Created!</strong> New Blog created successfully.
                        </div>
                    </div>
                    <?php
                    }

                    if ($_SESSION['blog_error']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Blog Creation Failed!</strong> Error while creating blog.
                        </div>
                    </div>
                    <?php
                    }


                    ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Post (New Blog Post)</h4>
                                <p class="card-description">
                                    If you can't find a solution to your problems in our knowledgebase, you can submit a
                                    ticket by selecting the appropriate department below & subject below. Tickets can
                                    also be created by simply sending an email. Ticket responses can also be created by
                                    replying to the same email.
                                </p>
                                <form method="POST" action="" enctype="multipart/form-data" class="needs-validation"
                                    novalidate>
                                    <div class="row g-3">
                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="formFile" class="form-label">Blog Title</label>
                                            <input type="text" class="form-control" placeholder="Blog Title"
                                                aria-label="Blog Title" id="blogtitle"  name="_blogtitle" required>
                                            <div class="invalid-feedback">Blog Title Needed</div>
                                            <div id="wordCountDisplay" style="margin: 10px 5px; display: none;" >
                                                <p style="color: red;" >Word Count <strong style="color: red;" id="wordCount" ></strong> </p>
                                            </div>
                                        </div>

                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <?php _showCategoryOptions() ?>

                                        </div>
                                    </div>

                                    <div class="row g-3">

                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <?php _showSubCategoryOptions() ?>

                                        </div>

                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="formFile" class="form-label">Featured Image</label>
                                            <input class="form-control" name="file" type="file" id="formFile" required>
                                            <div class="invalid-feedback">Featured Image Required</div>
                                        </div>
                                    </div>


                                    <div class="row g-3">



                                        <div class="col" style="margin: 15px 0  15px 10px;">
                                            <label class="checkbox-inline">
                                                <input name="_status" type="checkbox"> &nbsp; Is Active
                                            </label>
                                        </div>


                                    </div>


                                    <div class="row g-3">
                                        <div class="col">
                                            <label for="mytextarea" class="form-label">Blog Description</label>
                                            <textarea name="_blogdesc" id="mytextarea" required></textarea>
                                            <div class="invalid-feedback">Blog Description Required </div>
                                        </div>
                                    </div>


                                    <div class="row g-3">
                                        <div class="col" style="margin-top: 30px;">
                                            <label for="_blogmetadesc" class="form-label">Meta Description</label>
                                            <textarea name="_blogmetadesc" id="metaDescriptionInput" rows="5" class="form-control"
                                                required></textarea>
                                            <div class="invalid-feedback">Blog Meta Description Required </div>
                                        </div>
                                        <div id="metaDescwordCountDisplay" style="margin: 10px 5px; display: none;" >
                                                <p style="color: red;" >Word Count <strong style="color: red;" id="metaDescwordCount" ></strong> </p>
                                        </div>
                                    </div>

                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 150px;margin-left: -10px"
                                            class="btn btn-primary">Create Blog</button>
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

        <script>
            const getSubCategory = (val) => {
                $.ajax({
                    type: "POST",
                    url: "getSubCategory.php",
                    data: 'catid=' + val,
                    success: function (data) {
                        $(`#subcategoryId`).html(data);
                    }
                });
            }

            let blogtitle = document.getElementById('blogtitle');
            blogtitle.addEventListener('input',(ele)=>{
                let value = ele.target.value;
                if(value.length > 0){

                    let wordCountDisplay = document.getElementById('wordCountDisplay');
                    let wordCount = document.getElementById('wordCount');
                    wordCountDisplay.style.display = 'block'
                    wordCount.innerText = value.length;
                }
            })

            // let metaDescriptionInput = document.getElementById('metaDescriptionInput');
            // metaDescriptionInput.addEventListener('input',(ele)=>{
            //     let value = ele;
            //     console.log(value);
            //     if(value.length > 0){

            //         let wordCountDisplay = document.getElementById('metaDescwordCountDisplay');
            //         let wordCount = document.getElementById('metaDescwordCount');
            //         wordCountDisplay.style.display = 'block'
            //         wordCount.innerText = value.length;
            //     }
            // })


            


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