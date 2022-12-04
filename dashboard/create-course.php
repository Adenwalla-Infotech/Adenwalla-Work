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
if (isset($_SESSION['course_success']) || !isset($_SESSION['course_success'])) {
    $_SESSION['course_success'] = false;
}
if (isset($_SESSION['course_error']) || !isset($_SESSION['course_error'])) {
    $_SESSION['course_error'] = false;
}


require('../includes/_functions.php');

if (isset($_POST['submit'])) {

    $coursename = $_POST['coursename'];
    $courseDesc = $_POST['courseDesc'];
    $whatlearn = $_POST['whatlearn'];
    $requirements = $_POST['requirements'];
    $eligibitycriteria = $_POST['eligibitycriteria'];
    $capacity = $_POST['capacity'];
    $pricing = $_POST['pricing'];
    $teacheremailid = $_POST['teacheremailid'];
    $categoryid = $_POST['categoryId'];
    $subcategoryid = $_POST['subcategoryId'];
    $coursetype = $_POST['coursetype'];

    $coursechannel = $_POST['coursechannel'];
    $evaluationlink = $_POST['evaluationlink'];
    $courselevel = $_POST['courselevel'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    $discountprice = $_POST['discountprice'];


    if ($_FILES["thumbnail"]["name"] != '') {
        $thumbnail = $_FILES["thumbnail"]["name"];
        $extension = substr($thumbnail, strlen($thumbnail) - 4, strlen($thumbnail));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $thumbnailimg = md5($thumbnail) . $extension;
            move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "../uploads/coursethumbnail/" . $thumbnailimg);
        }
    }

    if ($_FILES["banner"]["name"] != '') {
        $banner = $_FILES["banner"]["name"];
        $extension = substr($banner, strlen($banner) - 4, strlen($banner));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $bannerimg = md5($banner) . $extension;
            move_uploaded_file($_FILES["banner"]["tmp_name"], "../uploads/coursebanner/" . $bannerimg);
        }
    }


    if (isset($_POST['isactive'])) {
        $isactive = $_POST['isactive'];
    } else {
        $isactive = false;
    }

    if (isset($_POST['enrollstatus'])) {
        $enrollstatus = $_POST['enrollstatus'];
    } else {
        $enrollstatus = false;
    }

    _createCourse($coursename, $courseDesc, $whatlearn, $requirements, $eligibitycriteria, $capacity, $enrollstatus, $thumbnailimg, $bannerimg, $pricing, $isactive, $teacheremailid, $categoryid, $subcategoryid, $coursetype, $coursechannel, $courselevel, $evaluationlink, $startdate, $enddate ,$discountprice);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Course |
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
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

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

                    if ($_SESSION['course_success']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Course Created!</strong> New course created successfully.
                        </div>
                    </div>
                    <?php
                    }

                    if ($_SESSION['course_error']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Course Creatation Failed</strong>
                        </div>
                    </div>
                    <?php
                    }

                    ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create Course</h4>
                                <p class="card-description">
                                    Before you start writing about your new topic, it's important to do some research.
                                    This will help you to understand the topic better, This will make it easier for you
                                    to write about the topic, and it will also make it more likely that people will be
                                    interested in reading what you have to say.
                                </p>
                                <form method="POST" action="" enctype="multipart/form-data" class="needs-validation"
                                    novalidate>

                                    <div class="row g-3">
                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <?php _showCategoryOptions() ?>

                                        </div>
                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <?php _showSubCategoryOptions() ?>

                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label for="teacheremailid" class="form-label">Teacher Email</label>
                                            <select id="teacheremailid" name="teacheremailid"
                                                class="form-control select2" required>
                                                <option>Select Teacher</option>
                                                <?php _getTeachers() ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="coursetype" class="form-label">Course Type</label>
                                            <select name="coursetype" id="coursetype" class="form-control  form-control-lg" required>
                                                <option selected value="">Type</option>

                                                <option value="Recorded">Recorded</option>
                                                <option value="Live">Live</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col-lg-6">
                                            <label for="pricing" class="form-label">Course Price</label>
                                            <input type="number" class="form-control" name="pricing" id="pricing"
                                                placeholder="Price" required>
                                            <div class="invalid-feedback">Please type correct pricing</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="capacity" class="form-label">Capacity</label>
                                            <input type="number" class="form-control" name="capacity" id="capacity"
                                                placeholder="Capacity" required>
                                            <div class="invalid-feedback">Please type correct capacity</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col-lg-6">
                                        <label for="courselevel" class="form-label">Course Level</label>
                                        <select name="courselevel" id="courselevel" class="form-control  form-control-lg" required>
                                            <option selected value="">Level</option>
                                            <option value="Beginner">Beginner</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Advanced">Advanced</option>

                                        </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="evaluationlink" class="form-label">Evaluation Link</label>
                                            <input type="text" class="form-control" name="evaluationlink" id="evaluationlink"
                                                required>
                                            <div class="invalid-feedback">Please type correct link</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col-lg-6">
                                            <label for="startdate" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" name="startdate" id="startdate"
                                                required>
                                            <div class="invalid-feedback">Please type correct date</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="enddate" class="form-label">End Date</label>
                                            <input type="date" class="form-control" name="enddate" id="enddate"
                                                required>
                                            <div class="invalid-feedback">Please type correct date</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                       
                                        <div class="col-lg-6">
                                            <label for="coursechannel" class="form-label">Course Channel</label>
                                            <input type="text" class="form-control" name="coursechannel" id="coursechannel"
                                                required>
                                            <div class="invalid-feedback">Please type correct course channel</div>
                                        </div>
                                       
                                        <div class="col-lg-6">
                                            <label for="discountprice" class="form-label">Discount Price</label>
                                            <input type="text" class="form-control" name="discountprice" id="discountprice"
                                            placeholder="Discount Price"
                                                required>
                                            <div class="invalid-feedback">Please type correct course discount price</div>
                                        </div>
                                    </div>


                                    <div class="row g-3" style="margin-top: 10px;">


                                        <div class="col" style="margin-top: 40px;">
                                            <label class="checkbox-inline" style="margin-left: 5px;">
                                                <input name="enrollstatus" value="true" type="checkbox"> &nbsp; Enroll
                                                Status
                                            </label>
                                        </div>

                                        <div class="col" style="margin-top: 40px;">
                                            <label class="checkbox-inline" style="margin-left: 5px;">
                                                <input name="isactive" value="true" type="checkbox"> &nbsp; Is Active
                                            </label>
                                        </div>
                                    </div>



                                    <div class="row g-3" style="margin-top: 30px;">


                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="thumbnail" class="form-label">Thumbnail Image</label>
                                            <input class="form-control" name="thumbnail" type="file" id="thumbnail"
                                                required>
                                            <div class="invalid-feedback">Featured Image Required</div>
                                        </div>

                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="banner" class="form-label">Banner Image</label>
                                            <input class="form-control" name="banner" type="file" id="banner" required>
                                            <div class="invalid-feedback">Featured Image Required</div>
                                        </div>
                                    </div>



                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="coursename" class="form-label">Course Name</label>
                                            <input class="form-control" name="coursename" type="text" id="coursename"
                                                required>
                                            <div class="invalid-feedback">Please type correct course name</div>
                                            <div id="wordCountDisplay" style="margin: 10px 5px; display: none;" >
                                                <p style="color: red;" >Word Count <strong style="color: red;" id="wordCount" ></strong> </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="courseDesc" class="form-label">Course Description</label>
                                            <textarea name="courseDesc" id="mytextarea" style="width:100%"
                                                rows="10"></textarea>
                                            <div class="invalid-feedback">Please type correct course desc</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="eligibitycriteria" class="form-label">Course Eligibility
                                                Criteria</label>
                                            <textarea name="eligibitycriteria" id="mytextarea" style="width:100%"
                                                rows="10"></textarea>
                                            <div class="invalid-feedback">Please type correct criteria</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="whatlearn" class="form-label">What will you Learn</label>
                                            <textarea name="whatlearn" id="mytextarea" style="width:100%"
                                                rows="10"></textarea>
                                            <div class="invalid-feedback">Please type correct course learning</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="requirements" class="form-label">Requirements</label>
                                            <textarea name="requirements" id="mytextarea" style="width:100%"
                                                rows="10"></textarea>
                                            <div class="invalid-feedback">Please type correct course requirements</div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 200px;margin-left: -10px"
                                            class="btn btn-primary">Create Course</button>

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
            $('.select2').select2();

            let courseTitle = document.getElementById('coursename');
            courseTitle.addEventListener('input',(ele)=>{
                let value = ele.target.value;
                if(value.length > 0){

                    let wordCountDisplay = document.getElementById('wordCountDisplay');
                    let wordCount = document.getElementById('wordCount');
                    wordCountDisplay.style.display = 'block'
                    wordCount.innerText = value.length;
                }
            })

        </script>


        <script src="../includes/_validation.js"></script>

</body>
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
    crossorigin="anonymous"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


</html>