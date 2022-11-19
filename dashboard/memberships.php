<?php

session_start();
require('../includes/_functions.php');

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

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard | <?php echo _siteconfig('_sitetitle'); ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
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
    .price-sec-wrap {
			width: 100%;
			float: left;
			padding: 60px 0;
			font-family: 'Lato', sans-serif;
		}
		.main-heading {
			text-align: center;
		    font-weight: 600;
		    padding-bottom: 15px;
		    position: relative;
		    text-transform: capitalize;
		    font-size: 24px;
		    margin-bottom: 25px;
		}
		.price-box {
			/* box-shadow: 0 0 35px rgba(0, 0, 0, 0.10); */
			padding: 20px;
			background: #fff;
    		border-radius: 4px;
		}
		.price-box ul {
    		padding: 10px 0px 30px;
		    margin: 17px 0 0 0;
		    list-style: none;
		    border-top: solid 1px #e9e9e9;
		}
		.price-box ul li {
			padding: 7px 0;
		    font-size: 14px;
		    color: #808080;
		}
		.price-box ul li .fas {
			color: #68AE4A;
			margin-right: 7px; 
			font-size: 12px;
		}
		.price-label {
			font-size: 16px;
		    font-weight: 600;
		    line-height: 1.34;
		    margin-bottom: 0;
		    padding: 6px 15px;
		    display: inline-block;
		    border-radius: 3px; 
		}
		.price-label.basic {
		    background: #E8EAF6;
		    color: #3F51B5;
		}
		.price-label.value {
		    background: #E8F5E9;
		    color: #4CAF50;
		}
		.price-label.premium {
		    background: #FBE9E7;
		    color: #FF5722;
		}
		.price {
			font-size: 24px;
		    line-height: 44px;
		    margin: 5px 0 6px;
		    font-weight: 900;
		}
		.price-info {
			font-size: 14px;
		    font-weight: 400;
		    line-height: 1.67;
		    color: inherit;
		    width: 100%;
		    margin: 0;
			margin-top: -10px;
		    color: #989898;
		}
		.plan-btn {
		  text-transform: uppercase;
		  font-weight: 600;
		  display: block;
		  padding: 11px 30px;
		  border: 2px solid #4B49AC;
		  color: #000;
		  margin-top: -10px;
		  overflow: hidden;
		  position: relative;
		  z-index: 1;
		  margin: 0;
		  border-radius: 5px;
		  text-decoration: none;
		  width: 100%;
		  text-align: center;
		  font-size: 14px;
		}
		.plan-btn::after {
		  position: absolute;
		  left: -100%;
		  top: 0;
		  content: "";
		  height: 100%;
		  width: 100%;
		  background: #4B49AC;
		  z-index: -1;
		  /* transition: all 0.35s ease-in-out; */
		}
		.plan-btn:hover::after {
		  left: 0;
		}
		.plan-btn:hover, 
		.plan-btn:focus {
			text-decoration: none;
			color: #fff;
		    border: 2px solid #4B49AC;
		}
		@media (max-width: 991px) {
			.price-box {
				margin-bottom: 20px;
			}
		}
		@media (max-width: 575px) {
			.main-heading {
				font-size: 21px;
			}
			.price-box {
				margin-bottom: 20px;
			}
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
                <?php _allmemberships(); ?>
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
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>

</html>