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

require('../includes/_functions.php');
require('../includes/_config.php');


if (isset($_GET['del'])) {
    $_id = $_GET['id'];
    _deleteLesson($_id);
}


$record_per_page = 5;
$page = '';
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $record_per_page;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Lesson |
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
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Manage Lesson</h4>
                                <p class="card-description">
                                    Web Help Desk uses tickets to manage service requests. These tickets can be
                                    initiated through email, created in the application, and imported from another
                                    application. Techs, admins, and clients can also manage tickets through email or
                                    through the application in a web browser.
                                </p>
                                <form method="POST" action="">
                                    <div class="row">

                                        <div class="col-lg-3" style="margin-bottom: 20px;">
                                            <?php

                                            $sql = "SELECT * FROM `tblcourse`";
                                            $query = mysqli_query($conn, $sql);
                                            if ($query) { ?>
                                            <label for="courseid" class="form-label">Select Course</label>
                                            <select style="height: 46px;" id="courseid" name="courseid"
                                                class="form-control form-control-lg">
                                                <option selected disabled>Course</option>
                                                <?php
                                                foreach ($query as $data) {
                                                ?>
                                                <option value="<?php echo $data['_id']; ?>">
                                                    <?php echo $data['_coursename']; ?>
                                                </option>
                                                <?php
                                                }
                                                ?>

                                            </select>
                                            <div class="invalid-feedback">Please select proper course</div>
                                            <?php
                                            }

                                            ?>

                                        </div>

                                        <div class="col-lg-3" style="margin-bottom: 20px;">
                                            <label for="lessonname" class="form-label">Lesson Name</label>
                                            <input type="text" class="form-control form-control-md" name="lessonname"
                                                placeholder="Lesson name">
                                        </div>


                                        <div class="col-lg-3" style="margin-bottom: 20px;">
                                            <label for="createdat" class="form-label">Lesson Date</label>
                                            <input type="date" class="form-control form-control-sm" name="createdat">
                                        </div>

                                        <div class="col-lg-2" style="margin-top: 30px;" >
                                            <button name="search"
                                                class="btn btn-block btn-primary btn-sm font-weight-medium auth-form-btn"
                                                style="height:40px" name="submit"><i
                                                    class="mdi mdi-account-search"></i>&nbsp;SEARCH</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="example" class="display table expandable-table"
                                                style="width:100%">
                                                <thead>
                                                    <tr>

                                                        <th>No</th>
                                                        <th>Course Name</th>
                                                        <th>Lesson Name</th>
                                                        <th>Status</th>
                                                        <th>Lesson Type</th>
                                                        <th>Availability</th>
                                                        <th>Created at</th>
                                                        <th>Updated at</th>
                                                        <th>Action</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: left;margin-left: 30px">
                                                    <?php
                                                    if (isset($_POST['search'])) {

                                                        $lessonname = $_POST['lessonname'];
                                                        $createdat = $_POST['createdat'];


                                                        if ($lessonname) {
                                                            _getLessons('', $lessonname, '', '', '');
                                                        } else if ($createdat) {
                                                            _getLessons('', '', $createdat, '', '');
                                                        }


                                                        if (isset($_POST['courseid'])) {
                                                            $courseid = $_POST['courseid'];
                                                            _getLessons($courseid, '', '', '', '');
                                                        }


                                                    }
                                                    if (!isset($_POST['search'])) {
                                                        _getLessons('', '', '', $start_from, $record_per_page);
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <nav aria-label="Page navigation example" style="margin-top: 30px;">
                                    <ul class="pagination">
                                        <?php
                                        $query = mysqli_query($conn, "SELECT * FROM `tbllessons`");
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
                        <a href='manage-lesson?page=" . ($page - 1) . "' class='page-link'>Previous</a>
                      </li>";
                                        }
                                        if ($total_records > 5) {
                                            for ($i = 1; $i <= $total_pages; $i++) {
                                                echo "
                      <li class='page-item'><a class='page-link' href='manage-lesson?page=" . $i . "'>$i</a></li>";
                                            }
                                        }
                                        if ($page <= $end_loop) {
                                            echo "<li class='page-item'>
                        <a class='page-link' href='manage-lesson?page=" . ($page + 1) . "'>Next</a>
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
        <script>
            $('.select2').select2();
        </script>
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