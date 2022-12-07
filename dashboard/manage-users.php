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
  _deleteuser($_id);
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
  <title>Manage Users |
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
<style>
  .table:nth-of-type(0) { display:none; }
</style>
<body>
  <div class="container-scroller">
    <!-- partial -->
    <?php include('templates/_header.php'); ?>
    <div class="container-fluid page-body-wrapper">

      <?php include('templates/_sidebar.php'); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Manage users (Registered Members)
                  <button class="btn btn-success btn-sm " onclick="exportToExcel()"
                    style="float: right; margin-bottom: 10px; "><i class="mdi mdi-export"></i>&nbsp;Export</button>
                </h4>
                <p class="card-description">
                  This section provides information you need to use the Worklist Users module of Worklist Console. This
                  module allows you to manage users, groups, and roles defined in the default security realm. You must
                  be logged in as a member of the Administrators or IntegrationAdministrators group to add, delete, or
                  modify a user, group, or role
                </p>
                <form method="POST" action="">
                  <div class="row">
                    <div class="col-lg-3" style="margin-bottom: 20px;">
                      <input type="text" class="form-control form-control-sm" name="useremail" placeholder="Email Id">
                    </div>
                    <div class="col-lg-3" style="margin-bottom: 20px;">
                      <select style="height: 40px;" name="usertype" class="form-control form-control-sm"
                        id="exampleFormControlSelect2" required>
                        <option value="0">Student</option>
                        <option value="1">Teacher</option>
                        <option value="2">Site Admin</option>
                      </select>
                    </div>
                    <div class="col-lg-3" style="margin-bottom: 20px;">
                      <input type="date" class="form-control form-control-sm" name="createdat">
                    </div>
                    <div class="col-lg-2" style="margin-bottom: 20px;">
                      <button name="search" class="btn btn-block btn-primary btn-sm font-weight-medium auth-form-btn"
                        style="height:40px" name="submit"><i class="mdi mdi-account-search"></i>&nbsp;SEARCH</button>
                    </div>
                  </div>
                </form>
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table id="example" class="display table expandable-table" style="width:100%">
                        <thead>
                          <tr>
                            <th>Username</th>
                            <th>Email ID</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Verified</th>
                            <th>Created at</th>
                            <th>Updated at</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody class="table-group-divider" style="text-align: left;margin-left: 30px">
                          <?php
                          if (isset($_POST['search'])) {

                            $useremail = $_POST['useremail'];
                            $usertype = $_POST['usertype'];
                            $createdat = $_POST['createdat'];

                            if ($useremail != '') {
                              _getuser($useremail, '', '', '', '');
                            } else if ($createdat != '') {
                              _getuser('', '', $createdat, '', '');
                            } else if ($usertype != '') {
                              _getuser('', $usertype, '', '', '');
                            }

                          }
                          if (!isset($_POST['search'])) {
                            _getuser('', '', '', $record_per_page, $start_from);
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <nav aria-label="Page navigation example" style="margin-top: 10px;">
                  <ul class="pagination">
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM `tblusers`");
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
                        <a href='manage-users?page=" . ($page - 1) . "' class='page-link'>Previous</a>
                      </li>";
                    }
                    if ($total_records > 5) {
                      for ($i = 1; $i <= $total_pages; $i++) {
                        echo "
                      <li class='page-item'><a class='page-link' href='manage-users?page=" . $i . "'>$i</a></li>";
                      }
                    }
                    if ($page <= $end_loop) {
                      echo "<li class='page-item'>
                        <a class='page-link' href='manage-users?page=" . ($page + 1) . "'>Next</a>
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
    <script src="../assets/js/table2excel.js"></script>
    <script>
      var table2excel = new Table2Excel();

      const exportToExcel = () => {
        table2excel.export(document.querySelectorAll("table.table"));
      }

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