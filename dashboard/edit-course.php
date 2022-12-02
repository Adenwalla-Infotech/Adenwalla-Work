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

if (isset($_SESSION['slide_success']) || !isset($_SESSION['slide_success'])) {
    $_SESSION['slide_success'] = false;
}
if (isset($_SESSION['slide_error']) || !isset($_SESSION['slide_error'])) {
    $_SESSION['slide_error'] = false;
}

if (isset($_SESSION['slide_update_success']) || !isset($_SESSION['slide_update_success'])) {
    $_SESSION['slide_update_success'] = false;
}
if (isset($_SESSION['slide_update_error']) || !isset($_SESSION['slide_update_error'])) {
    $_SESSION['slide_update_error'] = false;
}


$id = $_GET['id'];

require('../includes/_functions.php');
require('../includes/_config.php');


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
    else{
        $thumbnailimg = _getSingleCourse($id,'_thumbnail');
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
    else{
        $bannerimg = _getSingleCourse($id,'_banner');
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

    _updateCourse($id, $coursename, $courseDesc, $whatlearn, $requirements,$eligibitycriteria, $capacity, $enrollstatus, $thumbnailimg, $bannerimg, $pricing, $isactive, $teacheremailid, $categoryid, $subcategoryid, $coursetype, $coursechannel, $courselevel, $evaluationlink, $startdate, $enddate ,$discountprice);
}

$record_per_page = 5;
$page = '';
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $record_per_page;


if (isset($_POST['addSlide'])) {

    $caption = $_POST['caption'];

    if ($_FILES["banner"]["name"] != '') {
        $banner = $_FILES["banner"]["name"];
        $extension = substr($banner, strlen($banner) - 4, strlen($banner));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $_slideurl = md5($banner) . $extension;
            move_uploaded_file($_FILES["banner"]["tmp_name"], "../uploads/banner/" . $_slideurl);
        }
    }


    _createSlide($id, $_slideurl, $caption);

}

if (isset($_GET['del'])) {

    $_courseid = $_GET['id'];
    $slideid = $_GET['slideid'];

    _deleteSlide($slideid, $_courseid);
}

if (isset($_POST['editSlide'])) {

    $courseid = $_POST['courseid'];
    $slideid = $_POST['slideid'];

    $caption = $_POST['caption'];
  
    if ($_FILES["banner"]["name"] != '') {
        $banner = $_FILES["banner"]["name"];
        $extension = substr($banner, strlen($banner) - 4, strlen($banner));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $_slideurl = md5($banner) . $extension;
            move_uploaded_file($_FILES["banner"]["tmp_name"], "../uploads/banner/" . $_slideurl);
        }
    }
    else {
        $_slideurl = _getSingleSlide($slideid , $courseid , '_slideurl');
    }




    _updateSlide($slideid, $courseid, $_slideurl , $caption);

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Course |
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

                    if ($_SESSION['course_success']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Course Updated!</strong> New course created successfully.
                        </div>
                    </div>
                    <?php
                    }

                    if ($_SESSION['course_error']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Course Updation Failed</strong>
                        </div>
                    </div>
                    <?php
                    }

                    if ($_SESSION['slide_success']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Slide Added!</strong> New Slide Added successfully.
                        </div>
                    </div>
                    <?php
                    }

                    if ($_SESSION['slide_error']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Slide Creation Failed</strong>
                        </div>
                    </div>
                    <?php

                    }

                    if ($_SESSION['slide_update_success']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Slide Updated!</strong> Slide Updated successfully.
                        </div>
                    </div>
                    <?php
                    }

                    if ($_SESSION['slide_update_error']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Slide Updation Failed</strong>
                        </div>
                    </div>
                    <?php

                    }

                    ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Course</h4>
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
                                            <?php
                                            $categoryid = _getSingleCourse($id, '_categoryid');
                                            _showCategoryOptions($categoryid)
                                                ?>

                                        </div>
                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <?php
                                            $subcategoryid = _getSingleCourse($id, '_subcategoryid');
                                            _showSubCategoryOptions($subcategoryid)
                                                ?>

                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label for="teacheremailid" class="form-label">Teacher Email</label>
                                            <select id="teacheremailid" name="teacheremailid"
                                                class="form-control select2" required>
                                                <?php 
                                                        $teacherid = _getSingleCourse($id, '_teacheremailid');
                                                        _getTeachers($teacherid);
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="coursetype" class="form-label">Course Type</label>
                                            <select name="coursetype" id="coursetype" class="form-control" required>

                                                <?php

                                                $type = _getSingleCourse($id, '_coursetype');

                                                if ($type == 'Live') {

                                                ?>
                                                <option value="Recorded">Recorded</option>
                                                <option selected value="Live">Live</option>
                                                <?php

                                                } else {
                                                ?>
                                                <option selected value="Recorded">Recorded</option>
                                                <option value="Live">Live</option>
                                                <?php
                                                }

                                                ?>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 10px;">
                                        <div class="col-lg-6">
                                            <label for="pricing" class="form-label">Course Price</label>
                                            <input type="number" class="form-control" name="pricing" id="pricing"
                                                value="<?php echo _getSingleCourse($id, '_pricing') ?>"
                                                placeholder="Price" required>
                                            <div class="invalid-feedback">Please type correct pricing</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="capacity" class="form-label">Capacity</label>
                                            <input type="number" class="form-control" name="capacity" id="capacity"
                                                value="<?php echo _getSingleCourse($id, '_capacity') ?>"
                                                placeholder="Capacity" required>
                                            <div class="invalid-feedback">Please type correct capacity</div>
                                        </div>
                                    </div>



                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col-lg-6">
                                            <label for="courselevel" class="form-label">Course Level</label>
                                            <select name="courselevel" id="courselevel" class="form-control  " required>
                                                <?php
                                                
                                                    $level =  _getSingleCourse($id, '_courselevel') ;

                                                    if($level=='Beginner'){
                                                        ?>
                                                            <option selected value="Beginner">Beginner</option>
                                                            <option value="Intermediate">Intermediate</option>
                                                            <option value="Advanced">Advanced</option>
                                                        <?php
                                                    }
                                                    if($level=='Intermediate'){
                                                        ?>
                                                            <option value="Beginner">Beginner</option>
                                                            <option selected value="Intermediate">Intermediate</option>
                                                            <option value="Advanced">Advanced</option>
                                                        <?php
                                                    }
                                                    if($level=='Advanced'){
                                                        ?>
                                                            <option value="Beginner">Beginner</option>
                                                            <option value="Intermediate">Intermediate</option>
                                                            <option selected value="Advanced">Advanced</option>
                                                        <?php
                                                    }

                                                ?>

                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="evaluationlink" class="form-label">Evaluation Link</label>
                                            <input type="text" class="form-control" name="evaluationlink"
                                                id="evaluationlink"
                                                value="<?php echo _getSingleCourse($id, '_evuluationlink') ?>" required>
                                            <div class="invalid-feedback">Please type correct link</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col-lg-6">
                                            <label for="startdate" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" name="startdate" id="startdate"
                                                value="<?php echo _getSingleCourse($id, '_startdate') ?>" required>
                                            <div class="invalid-feedback">Please type correct date</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="enddate" class="form-label">End Date</label>
                                            <input type="date" class="form-control" name="enddate" id="enddate"
                                                value="<?php echo _getSingleCourse($id, '_enddate') ?>" required>
                                            <div class="invalid-feedback">Please type correct date</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col-lg-6">
                                            <label for="coursechannel" class="form-label">Course Channel</label>
                                            <input type="text" class="form-control" name="coursechannel"
                                                id="coursechannel"
                                                value="<?php echo _getSingleCourse($id, '_coursechannel') ?>" required>
                                            <div class="invalid-feedback">Please type correct course channel</div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="discountprice" class="form-label">Discount Price</label>
                                            <input type="text" class="form-control" name="discountprice" id="discountprice"
                                            placeholder="Discount Price"
                                            value="<?php echo _getSingleCourse($id, '_discountprice') ?>" required>
                                            <div class="invalid-feedback">Please type correct course discount price</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 10px;">


                                        <div class="col" style="margin-top: 40px;">
                                            <label class="checkbox-inline" style="margin-left: 5px;">

                                                <?php

                                                $isenroll = _getSingleCourse($id, '_enrollstatus');

                                                if ($isenroll) {
                                                ?>
                                                <input name="enrollstatus" value="true" checked type="checkbox"> &nbsp;
                                                Is Enroll
                                                <?php
                                                } else {
                                                ?>
                                                <input name="enrollstatus" value="true" type="checkbox"> &nbsp; Is
                                                Enroll
                                                <?php
                                                }

                                                ?>

                                            </label>
                                        </div>

                                        <div class="col" style="margin-top: 40px;">
                                            <label class="checkbox-inline" style="margin-left: 5px;">
                                                <?php

                                                $_status = _getSingleCourse($id, '_status');

                                                if ($_status) {
                                                ?>
                                                <input name="isactive" value="true" checked type="checkbox"> &nbsp; Is
                                                Active
                                                <?php
                                                } else {
                                                ?>
                                                <input name="isactive" value="true" type="checkbox"> &nbsp; Is Active
                                                <?php
                                                }

                                                ?>

                                            </label>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 30px;">


                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="thumbnail" class="form-label">Thumbnail Image</label>
                                            <input class="form-control" name="thumbnail" type="file" id="thumbnail">
                                            <a href="../uploads/coursethumbnail/<?php echo _getSingleCourse($id, '_thumbnail'); ?>"
                                                target="_blank">Open Featured Image &nbsp;<svg
                                                    xmlns="http://www.w3.org/2000/svg" style="width: 15px;"
                                                    viewBox="0 0 512 512">
                                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                    <path
                                                        d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z" />
                                                </svg></a>
                                            <div class="invalid-feedback">Featured Image Required</div>
                                        </div>

                                        <div class="col-lg-6" style="margin-bottom: 20px;">
                                            <label for="banner" class="form-label">Banner Image</label>
                                            <input class="form-control" name="banner" type="file" id="banner">
                                            <a href="../uploads/coursebanner/<?php echo _getSingleCourse($id, '_banner'); ?>"
                                                target="_blank">Open Featured Image &nbsp;<svg
                                                    xmlns="http://www.w3.org/2000/svg" style="width: 15px;"
                                                    viewBox="0 0 512 512">
                                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                    <path
                                                        d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z" />
                                                </svg></a>
                                            <div class="invalid-feedback">Featured Image Required</div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="coursename" class="form-label">Course Name</label>
                                            <input class="form-control" name="coursename" type="text" id="coursename"
                                                value="<?php echo _getSingleCourse($id, '_coursename') ?>" required>
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
                                                rows="10"><?php echo _getSingleCourse($id, '_coursedescription') ?></textarea>
                                            <div class="invalid-feedback">Please type correct course desc</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="eligibitycriteria" class="form-label">Course Eligibility
                                                Criteria</label>
                                            <textarea name="eligibitycriteria" id="mytextarea" style="width:100%"
                                                rows="10"><?php echo _getSingleCourse($id, '_eligibilitycriteria') ?></textarea>
                                            <div class="invalid-feedback">Please type correct criteria</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="whatlearn" class="form-label">What will you Learn</label>
                                            <textarea name="whatlearn" id="mytextarea" style="width:100%"
                                                rows="10"><?php echo _getSingleCourse($id, '_whatlearn') ?></textarea>
                                            <div class="invalid-feedback">Please type correct course learning</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="requirements" class="form-label">Requirements</label>
                                            <textarea name="requirements" id="mytextarea" style="width:100%"
                                                rows="10"><?php echo _getSingleCourse($id, '_requirements') ?></textarea>
                                            <div class="invalid-feedback">Please type correct course requirements</div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 200px;margin-left: -10px"
                                            class="btn btn-primary">Update Course</button>
                                        <button type="button"
                                            class="btn btn-primary btn-sm font-weight-medium auth-form-btn"
                                            style="height:40px; float:right; " data-bs-toggle="modal"
                                            data-bs-target="#exampleModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="white" style="width: 15px;"
                                                viewBox="0 0 448 512">
                                                <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                <path
                                                    d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" />
                                            </svg>&nbsp;&nbsp;Add Slides
                                        </button>
                                    </div>

                                </form>
                            </div>

                            <div class="card-body" style="margin-top: 30px ;">
                                <h4 class="card-title">Manage Slide </h4>
                                <p class="card-description">
                                    From here, you'll see a list of all the categories on your site. You can edit or
                                    delete them from here. You can also change the order of your categories by dragging
                                    and dropping them into the order you
                                </p>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="example" class="display expandable-table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Course Name</th>
                                                        <th>View Slide</th>
                                                        <th>Created at</th>
                                                        <th>Updated at</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: left;margin-left: 30px">
                                                    <?php
                                                    _getSlides($id,$start_from, $record_per_page);
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <nav aria-label="Page navigation example" style="margin-top: 10px;">
                                    <ul class="pagination">
                                        <?php
                                        $query = mysqli_query($conn, "SELECT * FROM `tblslides`");
                                        $total_records = mysqli_num_rows($query);
                                        $total_pages = ceil($total_records / $record_per_page);
                                        $start_loop = $page;
                                        $difference = $total_pages - $page;
                                        if ($difference <= 4) {
                                            $start_loop = $total_pages - 4;
                                        }
                                        $end_loop = $start_loop + 3;
                                        if ($page > 1) {
                                            echo "<li class='page-item'>
                        <a href='edit-course.php?id=$id&page=" . ($page - 1) . "' class='page-link'>Previous</a>
                      </li>";
                                        }
                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            echo "
                      <li class='page-item'><a class='page-link' href='edit-course.php?id=$id&page=" . $i . "'>$i</a></li>";
                                        }
                                        if ($page <= $end_loop) {
                                            echo "<li class='page-item'>
                        <a class='page-link' href='edit-course.php?id=$id&page=" . ($page + 1) . "'>Next</a>
                      </li>";
                                        } ?>
                                    </ul>
                                </nav>
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



        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-content" style="padding: 10px;">
                        <div class="modal-header" style="padding: 0px;margin-bottom: 20px;padding-bottom:10px">
                            <h4 class="modal-title fs-5" id="exampleModalLabel">Add Slide</h4>
                            <button type="button" class="btn-close" style="border: none;;background-color:white"
                                data-bs-dismiss="modal" aria-label="Close"><svg style="width: 15px;"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                    <path
                                        d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" />
                                </svg></button>
                        </div>
                        <div class="modal-body" style="padding: 0px;">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="inputEmail4" class="form-label">Banner Image</label>
                                    <input type="file" name="banner" class="form-control">
                                </div>

                            </div>

                            <div class="row" style="margin-top: 30px;">
                                <div class="col">
                                    <label for="caption" class="form-label">Caption</label>
                                    <textarea name="caption" id="caption" style="width:100%" rows="10"></textarea>
                                    <div class="invalid-feedback">Please type caption</div>
                                </div>
                            </div>



                        </div>
                        <div class="modal-footer" style="padding: 0px;margin-top: 20px;padding-top:10px">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="addSlide" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="editBanner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="editBannerBody">

            </div>
        </div>



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

        const callEditSlide = (courseid, slideid) => {


            $.ajax({
                type: "POST",
                url: `editslidebanner.php`,
                data: {
                    "edit": true,
                    "courseid": courseid,
                    "slideid": slideid,
                },
                success: function(data) {
                    $(`#editBannerBody`).html(data);
                    $(`#editBanner`).modal("show");
                }
            });

        }

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
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
</script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</html>