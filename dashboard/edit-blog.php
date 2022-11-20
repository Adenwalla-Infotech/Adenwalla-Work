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
$_id = $_GET['id'];

require('../includes/_functions.php');

if (isset($_POST['submit'])) {

    $_blogtitle = $_POST['_blogtitle'];
    $_blogdesc = $_POST['_blogdesc'];
    $_blogcategory = $_POST['categoryId'];
    $_blogsubcategory = $_POST['subcategoryId'];
    $_blogmetadesc = $_POST['_blogmetadesc'];
    if (isset($_POST['isactive'])) {
        $_status = $_POST['isactive'];
    } else {
        $_status = null;
    }



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
    } else {
        $_blogimg =  _getSingleBlog($_id, '_blogimg');
    }


    updateBlog($_blogtitle, $_blogdesc, $_blogcategory, $_blogsubcategory, $_blogmetadesc, $_blogimg, $_status, $_id);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Blog | <?php echo _getSingleBlog($_id, '_blogtitle'); ?></title>
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
                                <strong>Blog Updated!</strong> Blog Updated successfully.
                            </div>
                        </div>
                    <?php
                    }

                    if ($_SESSION['blog_error']) {
                    ?>
                        <div id="liveAlertPlaceholder">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Blog Updation Failed!</strong> Error while updating blog.
                            </div>
                        </div>
                    <?php
                    }


                    ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Blog (Edit Blog Post)</h4>
                                <p class="card-description">
                                    If you can't find a solution to your problems in our knowledgebase, you can submit a ticket by selecting the appropriate department below & subject below. Tickets can also be created by simply sending an email. Ticket responses can also be created by replying to the same email.
                                </p>
                                <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <div class="row g-3">
                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="formFile" class="form-label">Blog Title</label>
                                            <input type="text" class="form-control" placeholder="Blog Title" aria-label="Blog Title" name="_blogtitle" value="<?php echo _getSingleBlog($_id, '_blogtitle') ?>" required>
                                            <div class="invalid-feedback">Blog Title Needed</div>
                                        </div>

                                        <div class="col-lg-6" style="margin-bottom: 20px;">

                                            <?php
                                            $categoryId = _getSingleBlog($_id, '_blogcategory');
                                            _showCategoryOptions($categoryId)
                                            ?>

                                        </div>
                                    </div>

                                    <div class="row g-3">

                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <?php
                                            $subcategoryId = _getSingleBlog($_id, '_blogsubcategory');
                                            _showSubCategoryOptions($subcategoryId)
                                            ?>

                                        </div>

                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="formFile" class="form-label">Featured Image</label>
                                            <input style="margin-bottom: 5px;" class="form-control" name="file" type="file" id="formFile">
                                            <a href="../uploads/blogsPics/<?php echo _getSingleBlog($_id, '_blogimg'); ?>" target="_blank">Open Featured Image &nbsp;<svg xmlns="http://www.w3.org/2000/svg" style="width: 15px;" viewBox="0 0 512 512">
                                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                    <path d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z" />
                                                </svg></a>
                                            <div class="invalid-feedback">Featured Image Required</div>
                                        </div>
                                    </div>


                                    <div class="row g-3">



                                        <div class="col" style="margin: 15px 0  15px 10px;">
                                            <label class="checkbox-inline">
                                                <?php
                                                if (_getSingleBlog($_id, '_status') == true) { ?>
                                                    <input name="isactive" value="false" checked type="checkbox">&nbsp;Is Active<?php } else { ?>
                                                    <input name="isactive" value="true" type="checkbox">&nbsp;Is Active<?php }
                                                                                                                        ?>
                                            </label>
                                        </div>


                                    </div>


                                    <div class="row g-3">
                                        <div class="col">
                                            <label for="mytextarea" class="form-label">Blog Description</label>
                                            <textarea name="_blogdesc" id="mytextarea" minlength="20" required><?php echo _getSingleBlog($_id, '_blogdesc') ?></textarea>
                                            <div class="invalid-feedback">Blog Description Required (More than 20 Words) </div>
                                        </div>
                                    </div>


                                    <div class="row g-3">
                                        <div class="col" style="margin-top: 30px;">
                                            <label for="_blogmetadesc" class="form-label">Meta Description</label>
                                            <textarea name="_blogmetadesc" style="line-height: 1.5em;" rows="3" class="form-control" minlength="5" required><?php echo _getSingleBlog($_id, '_blogmetadesc') ?></textarea>
                                            <div class="invalid-feedback">Blog Meta Description Required (More than 10 Words) </div>
                                        </div>
                                    </div>

                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 150px;margin-left: -10px" class="btn btn-primary">Update Blog</button>
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
                    success: function(data) {
                        $(`#subcategoryId`).html(data);
                    }
                });
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