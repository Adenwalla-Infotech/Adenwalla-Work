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


$id = $_GET['id'];

require('../includes/_functions.php');

if (isset($_POST['submit'])) {

    $_lessonname = $_POST['lessonname'];
    $_courseid = $_POST['courseid'];
    $_lessondescription = $_POST['lessonDescription'];
    $_availablity = $_POST['availablity'];

    $lessontype = $_POST['lessontype'];



    if (isset($_POST['isactive'])) {
        $isactive = $_POST['isactive'];
    } else {
        $isactive = false;
    }

    if ($_FILES["lessonfile"]["name"] != '') {
        $lessonfile = $_FILES["lessonfile"]["name"];
        $extension = substr($lessonfile, strlen($lessonfile) - 4, strlen($lessonfile));
        $allowed_extensions = array(".mp4", ".mkv", ".webm", ".avi");
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only mp4 / mkv/ webm /avi format allowed');</script>";
        } else {
            $lessonurl = '';
            $recorderfile = md5($lessonfile) . $extension;
            move_uploaded_file($_FILES["lessonfile"]["tmp_name"], "../uploads/recordedlesson/" . $recorderfile);
        }
    } else {
        $recorderfile = '';
        $lessonurl = $_POST['lessonurl'];
    }

    _updateLesson($id, $_courseid, $_lessonname, $lessontype, $lessonurl, $recorderfile, $_lessondescription, $isactive, $_availablity);

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Lesson |
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
                            <strong>Lesson Updated!</strong> Lesson Updated successfully.
                        </div>
                    </div>
                    <?php
                    }

                    if ($_SESSION['course_error']) {
                    ?>
                    <div id="liveAlertPlaceholder">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Lesson Updation Failed</strong>
                        </div>
                    </div>
                    <?php
                    }

                    ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Lesson</h4>
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

                                            $courseid = _getSingleLesson($id, '_courseid');
                                            _showCourses($courseid);

                                            ?>

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="availablity" class="form-label">Availablity</label>
                                            <input type="number" class="form-control  form-control-sm "
                                                name="availablity"
                                                value="<?php echo _getSingleLesson($id, '_availablity'); ?>"
                                                id="availablity" placeholder="Availablity" required>
                                            <div class="invalid-feedback">Please type correct capacity</div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label for="lessontype" class="form-label">Lesson Type</label>
                                            <select style="height: 46px;" id="lessontype" name="lessontype"
                                                class="form-control form-control-lg"
                                                onchange="setInputForLessonType(this.options[this.selectedIndex])"
                                                required>

                                                <?php

                                                $lessontype = _getSingleLesson($id, '_lessontype');

                                                if ($lessontype == 'Live') {
                                                ?>
                                                <option selected value="Live">Live</option>
                                                <option value="Recorded">Recorded</option>
                                                <?php
                                                } else {
                                                ?>
                                                <option value="Live">Live</option>
                                                <option selected value="Recorded">Recorded</option>
                                                <?php
                                                }

                                                ?>


                                            </select>
                                            <div class="invalid-feedback">Please select correct lessontype</div>
                                        </div>

                                        <div class="col-lg-6" style="display: none;" id="lessonurl">
                                            <label for="lessonurl" class="form-label">Lesson URl</label>
                                            <input type="text" class="form-control" name="lessonurl"
                                                placeholder="Lesson URl"
                                                value="<?php echo _getSingleLesson($id, '_lessonurl') ?>">
                                            <div class="invalid-feedback">Please type correct url</div>
                                        </div>

                                        <div class="col-lg-6" style="display: none;" id="lessonfile">
                                            <label for="lessonfile" class="form-label">Video Lecture</label>
                                            <input type="file" class="form-control" name="lessonfile"
                                                style="margin-bottom: 20px;">

                                            <?php

                                            $lecture = _getSingleLesson($id, '_recordedfilename');
                                            ;

                                            if ($lecture) {
                                            ?>
                                            <a href="../uploads/recordedlesson/<?php echo _getSingleLesson($id, '_recordedfilename'); ?>"
                                                target="_blank">Open Video Lecture &nbsp;<svg
                                                    xmlns="http://www.w3.org/2000/svg" style="width: 15px;"
                                                    viewBox="0 0 512 512">
                                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                    <path
                                                        d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z" />
                                                </svg></a>
                                            <?php

                                            }

                                            ?>


                                            <div class="invalid-feedback">Please upload correct file</div>
                                        </div>

                                    </div>

                                    <div class="row g-3" style="margin-top: 30px;">

                                        <div class="col" style="margin-top: 40px;">
                                            <label class="checkbox-inline" style="margin-left: 5px;">
                                                <?php

                                                $_status = _getSingleLesson($id, '_status');

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



                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="lessonname" class="form-label">Lesson Name</label>
                                            <input type="text" class="form-control" name="lessonname" id="lessonname"
                                                placeholder="Lesson Name"
                                                value="<?php echo _getSingleLesson($id, '_lessonname'); ?>" required>
                                            <div class="invalid-feedback">Please type correct Description</div>
                                        </div>
                                    </div>


                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="lessonDescription" class="form-label">Lesson Description</label>
                                            <textarea name="lessonDescription" id="mytextarea" style="width:100%"
                                                rows="10"><?php echo _getSingleLesson($id, '_lessondescription'); ?></textarea>
                                            <div class="invalid-feedback">Please type correct Description</div>
                                        </div>
                                    </div>

                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 200px;margin-left: -10px"
                                            class="btn btn-primary">Update Lesson</button>

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
            let lessontype = document.getElementById('lessontype');
            let lessonurl = document.getElementById('lessonurl');
            let lessonfile = document.getElementById('lessonfile');

            let value = lessontype.options[lessontype.selectedIndex].value;

            if (value == 'Live') {
                lessonurl.style.display = 'block'
                lessonurl.children[1].setAttribute('required', true);

                lessonfile.style.display = 'none'
                lessonfile.children[1].removeAttribute('required');
            } else if (value == 'Recorded') {
                lessonfile.style.display = 'block'
                lessonfile.children[1].setAttribute('required', true);

                lessonurl.style.display = 'none'
                lessonurl.children[1].removeAttribute('required', true);
            }

            const setInputForLessonType = (value) => {

                let input = value.value;

                if (input == 'Live') {
                    lessonurl.style.display = 'block'
                    lessonurl.children[1].setAttribute('required', true);

                    lessonfile.style.display = 'none'
                    lessonfile.children[1].removeAttribute('required');
                } else if (input == 'Recorded') {
                    lessonfile.style.display = 'block'
                    lessonfile.children[1].setAttribute('required', true);

                    lessonurl.style.display = 'none'
                    lessonurl.children[1].removeAttribute('required', true);
                }

            }
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