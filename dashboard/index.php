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
 .card-counter{
    box-shadow: 2px 2px 10px #DADADA;
    margin: 5px;
    padding: 20px 10px;
    background-color: #fff;
    height: 100px;
    border-radius: 5px;
    transition: .3s linear all;
  }

  .card-counter:hover{
    box-shadow: 4px 4px 20px #DADADA;
    transition: .3s linear all;
  }

  .card-counter.primary{
    background-color: #007bff;
    color: #FFF;
  }

  .card-counter.danger{
    background-color: #ef5350;
    color: #FFF;
  }  

  .card-counter.success{
    background-color: #66bb6a;
    color: #FFF;
  }  

  .card-counter.info{
    background-color: #26c6da;
    color: #FFF;
  }  

  .card-counter i{
    font-size: 5em;
    opacity: 0.2;
  }

  .card-counter .count-numbers{
    position: absolute;
    right: 35px;
    top: 20px;
    font-size: 32px;
    display: block;
  }

  .card-counter .count-name{
    position: absolute;
    right: 35px;
    top: 65px;
    font-style: italic;
    text-transform: capitalize;
    opacity: 0.5;
    display: block;
    font-size: 18px;
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
          <div class="row">
            <div class="col-md-3">
              <div class="card-counter primary">
                <svg style="width: 50px;margin-left:20px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"/></svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblusers','_userstatus','true'); ?></span>
                <span class="count-name">Active Users</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter danger">
                <svg style="width: 50px;margin-left:20px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M132.65,212.32,36.21,137.78A63.4,63.4,0,0,0,32,160a63.84,63.84,0,0,0,100.65,52.32Zm40.44,62.28A63.79,63.79,0,0,0,128,256H64A64.06,64.06,0,0,0,0,320v32a32,32,0,0,0,32,32H97.91A146.62,146.62,0,0,1,173.09,274.6ZM544,224a64,64,0,1,0-64-64A64.06,64.06,0,0,0,544,224ZM500.56,355.11a114.24,114.24,0,0,0-84.47-65.28L361,247.23c41.46-16.3,71-55.92,71-103.23A111.93,111.93,0,0,0,320,32c-57.14,0-103.69,42.83-110.6,98.08L45.46,3.38A16,16,0,0,0,23,6.19L3.37,31.46A16,16,0,0,0,6.18,53.91L594.53,508.63A16,16,0,0,0,617,505.82l19.64-25.27a16,16,0,0,0-2.81-22.45ZM128,403.21V432a48,48,0,0,0,48,48H464a47.45,47.45,0,0,0,12.57-1.87L232,289.13C173.74,294.83,128,343.42,128,403.21ZM576,256H512a63.79,63.79,0,0,0-45.09,18.6A146.29,146.29,0,0,1,542,384h66a32,32,0,0,0,32-32V320A64.06,64.06,0,0,0,576,256Z"/></svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblusers','_userstatus','false'); ?></span>
                <span class="count-name">In-active Users</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter success">
                <svg style="width: 50px;margin-left:20px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M622.3 271.1l-115.2-45c-4.1-1.6-12.6-3.7-22.2 0l-115.2 45c-10.7 4.2-17.7 14-17.7 24.9 0 111.6 68.7 188.8 132.9 213.9 9.6 3.7 18 1.6 22.2 0C558.4 489.9 640 420.5 640 296c0-10.9-7-20.7-17.7-24.9zM496 462.4V273.3l95.5 37.3c-5.6 87.1-60.9 135.4-95.5 151.8zM224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm96 40c0-2.5.8-4.8 1.1-7.2-2.5-.1-4.9-.8-7.5-.8h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c6.8 0 13.3-1.5 19.2-4-54-42.9-99.2-116.7-99.2-212z"/></svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblusers','_userverify','true'); ?></span>
                <span class="count-name">Verified Users</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter info">
                <svg style="width: 50px;margin-left:20px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M624 208H432c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h192c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm-400 48c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblusers','_userverify','false'); ?></span>
                <span class="count-name">Unverified Users</span>
              </div>
            </div>
            <a href="manage-users" style="margin-left: 20px;margin-top:10px">Manage Users &nbsp;&nbsp;<svg fill="blue" style="width: 13px;margin-top:-3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"/></svg></a>
          </div>
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-3">
              <div class="card-counter primary">
                <!-- Generator: Adobe Illustrator 22.0.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                <svg fill="white" style="margin-left:20px" width="52" height="52" version="1.1" id="lni_lni-ticket" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M60.5,25.6c1.2-0.1,2.2-1.1,2.2-2.4v-9c0-1.3-1.1-2.4-2.4-2.4H3.6c-1.3,0-2.4,1.1-2.4,2.4v9.2c0,1.3,1,2.4,2.2,2.4
                    c3.2,0.3,5.6,3.1,5.6,6.4c0,3.3-2.4,6-5.6,6.3c-1.2,0-2.3,1.1-2.3,2.3v9c0,1.3,1.1,2.4,2.4,2.4h56.7c1.3,0,2.4-1.1,2.4-2.4v-9
                    c0-1.3-1-2.4-2.2-2.4c-3.2-0.3-5.6-3.1-5.6-6.4C54.9,28.6,57.3,25.9,60.5,25.6z M59.3,41.7v7H31.1v-6.2c0-1-0.8-1.8-1.8-1.8
                    s-1.8,0.8-1.8,1.8v6.2H4.8v-6.9c4.5-0.8,7.9-4.8,7.9-9.6c0-4.8-3.4-8.9-7.9-9.7v-7.1h22.9v6.2c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8
                    v-6.2h28.1v6.9c-4.5,0.9-7.9,4.9-7.9,9.7C51.4,36.7,54.8,40.8,59.3,41.7z"/>
                  <path d="M29.4,27.6c-1,0-1.8,0.8-1.8,1.8v5.3c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8v-5.3C31.1,28.4,30.3,27.6,29.4,27.6z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tbltickets','_status','open'); ?></span>
                <span class="count-name">Open Tickets</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter danger">
                <!-- Generator: Adobe Illustrator 22.0.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                <svg fill="white" style="margin-left:20px" width="52" height="52" version="1.1" id="lni_lni-ticket" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M60.5,25.6c1.2-0.1,2.2-1.1,2.2-2.4v-9c0-1.3-1.1-2.4-2.4-2.4H3.6c-1.3,0-2.4,1.1-2.4,2.4v9.2c0,1.3,1,2.4,2.2,2.4
                    c3.2,0.3,5.6,3.1,5.6,6.4c0,3.3-2.4,6-5.6,6.3c-1.2,0-2.3,1.1-2.3,2.3v9c0,1.3,1.1,2.4,2.4,2.4h56.7c1.3,0,2.4-1.1,2.4-2.4v-9
                    c0-1.3-1-2.4-2.2-2.4c-3.2-0.3-5.6-3.1-5.6-6.4C54.9,28.6,57.3,25.9,60.5,25.6z M59.3,41.7v7H31.1v-6.2c0-1-0.8-1.8-1.8-1.8
                    s-1.8,0.8-1.8,1.8v6.2H4.8v-6.9c4.5-0.8,7.9-4.8,7.9-9.6c0-4.8-3.4-8.9-7.9-9.7v-7.1h22.9v6.2c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8
                    v-6.2h28.1v6.9c-4.5,0.9-7.9,4.9-7.9,9.7C51.4,36.7,54.8,40.8,59.3,41.7z"/>
                  <path d="M29.4,27.6c-1,0-1.8,0.8-1.8,1.8v5.3c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8v-5.3C31.1,28.4,30.3,27.6,29.4,27.6z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tbltickets','_status','closed'); ?></span>
                <span class="count-name">Closed Tickets</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter success">
                <!-- Generator: Adobe Illustrator 22.0.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                <svg fill="white" style="margin-left:20px" width="52" height="52" version="1.1" id="lni_lni-ticket" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M60.5,25.6c1.2-0.1,2.2-1.1,2.2-2.4v-9c0-1.3-1.1-2.4-2.4-2.4H3.6c-1.3,0-2.4,1.1-2.4,2.4v9.2c0,1.3,1,2.4,2.2,2.4
                    c3.2,0.3,5.6,3.1,5.6,6.4c0,3.3-2.4,6-5.6,6.3c-1.2,0-2.3,1.1-2.3,2.3v9c0,1.3,1.1,2.4,2.4,2.4h56.7c1.3,0,2.4-1.1,2.4-2.4v-9
                    c0-1.3-1-2.4-2.2-2.4c-3.2-0.3-5.6-3.1-5.6-6.4C54.9,28.6,57.3,25.9,60.5,25.6z M59.3,41.7v7H31.1v-6.2c0-1-0.8-1.8-1.8-1.8
                    s-1.8,0.8-1.8,1.8v6.2H4.8v-6.9c4.5-0.8,7.9-4.8,7.9-9.6c0-4.8-3.4-8.9-7.9-9.7v-7.1h22.9v6.2c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8
                    v-6.2h28.1v6.9c-4.5,0.9-7.9,4.9-7.9,9.7C51.4,36.7,54.8,40.8,59.3,41.7z"/>
                  <path d="M29.4,27.6c-1,0-1.8,0.8-1.8,1.8v5.3c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8v-5.3C31.1,28.4,30.3,27.6,29.4,27.6z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tbltickets','_status','resolved'); ?></span>
                <span class="count-name">Resolved Tickets</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter info">
                <!-- Generator: Adobe Illustrator 22.0.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                <svg fill="white" style="margin-left:20px" width="52" height="52" version="1.1" id="lni_lni-ticket" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M60.5,25.6c1.2-0.1,2.2-1.1,2.2-2.4v-9c0-1.3-1.1-2.4-2.4-2.4H3.6c-1.3,0-2.4,1.1-2.4,2.4v9.2c0,1.3,1,2.4,2.2,2.4
                    c3.2,0.3,5.6,3.1,5.6,6.4c0,3.3-2.4,6-5.6,6.3c-1.2,0-2.3,1.1-2.3,2.3v9c0,1.3,1.1,2.4,2.4,2.4h56.7c1.3,0,2.4-1.1,2.4-2.4v-9
                    c0-1.3-1-2.4-2.2-2.4c-3.2-0.3-5.6-3.1-5.6-6.4C54.9,28.6,57.3,25.9,60.5,25.6z M59.3,41.7v7H31.1v-6.2c0-1-0.8-1.8-1.8-1.8
                    s-1.8,0.8-1.8,1.8v6.2H4.8v-6.9c4.5-0.8,7.9-4.8,7.9-9.6c0-4.8-3.4-8.9-7.9-9.7v-7.1h22.9v6.2c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8
                    v-6.2h28.1v6.9c-4.5,0.9-7.9,4.9-7.9,9.7C51.4,36.7,54.8,40.8,59.3,41.7z"/>
                  <path d="M29.4,27.6c-1,0-1.8,0.8-1.8,1.8v5.3c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8v-5.3C31.1,28.4,30.3,27.6,29.4,27.6z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tbltickets','_status','pending'); ?></span>
                <span class="count-name">Pending Tickets</span>
              </div>
            </div>
            <a href="manage-tickets" style="margin-left: 20px;margin-top:10px">Manage Tickets&nbsp;&nbsp;<svg fill="blue" style="width: 13px;margin-top:-3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"/></svg></a>
          </div>	
          <div class="row" style="margin-top: 20px; margin-bottom:50px">
            <div class="col-md-3">
              <div class="card-counter primary">
                <svg fill="white" width="52" style="margin-left:10px" height="52" version="1.1" id="lni_lni-bookmark" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M16.2,62.8c-0.6,0-1.2-0.1-1.8-0.4c-1.6-0.7-2.6-2.3-2.6-4v-51c0-3.3,2.7-6.1,6.1-6.1h28.3c3.3,0,6.1,2.7,6.1,6.1v50.9
                    c0,1.7-1,3.3-2.6,4c-1.6,0.7-3.4,0.4-4.7-0.7l-11.4-10c-0.9-0.6-2.1-0.6-3,0L19.1,61.6C18.3,62.4,17.2,62.8,16.2,62.8z M17.9,4.8
                    c-1.4,0-2.6,1.1-2.6,2.6v51c0,0.5,0.4,0.7,0.5,0.8c0.2,0.1,0.6,0.2,1-0.1l11.6-10.2c2.2-1.7,5.2-1.7,7.4,0L36,49l11.2,9.9
                    c0.4,0.3,0.8,0.2,1,0.1c0.2-0.1,0.5-0.3,0.5-0.8V7.3c0-1.4-1.1-2.6-2.6-2.6H17.9z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblcourse','_status','true'); ?></span>
                <span class="count-name">Active Courses</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter danger">
                <svg fill="white" width="52" style="margin-left:10px" height="52" version="1.1" id="lni_lni-bookmark" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M16.2,62.8c-0.6,0-1.2-0.1-1.8-0.4c-1.6-0.7-2.6-2.3-2.6-4v-51c0-3.3,2.7-6.1,6.1-6.1h28.3c3.3,0,6.1,2.7,6.1,6.1v50.9
                    c0,1.7-1,3.3-2.6,4c-1.6,0.7-3.4,0.4-4.7-0.7l-11.4-10c-0.9-0.6-2.1-0.6-3,0L19.1,61.6C18.3,62.4,17.2,62.8,16.2,62.8z M17.9,4.8
                    c-1.4,0-2.6,1.1-2.6,2.6v51c0,0.5,0.4,0.7,0.5,0.8c0.2,0.1,0.6,0.2,1-0.1l11.6-10.2c2.2-1.7,5.2-1.7,7.4,0L36,49l11.2,9.9
                    c0.4,0.3,0.8,0.2,1,0.1c0.2-0.1,0.5-0.3,0.5-0.8V7.3c0-1.4-1.1-2.6-2.6-2.6H17.9z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblcourse','_status','false'); ?></span>
                <span class="count-name">In-active Courses</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter success">
                <svg fill="white" width="52" style="margin-left:10px" height="52" version="1.1" id="lni_lni-bookmark" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M16.2,62.8c-0.6,0-1.2-0.1-1.8-0.4c-1.6-0.7-2.6-2.3-2.6-4v-51c0-3.3,2.7-6.1,6.1-6.1h28.3c3.3,0,6.1,2.7,6.1,6.1v50.9
                    c0,1.7-1,3.3-2.6,4c-1.6,0.7-3.4,0.4-4.7-0.7l-11.4-10c-0.9-0.6-2.1-0.6-3,0L19.1,61.6C18.3,62.4,17.2,62.8,16.2,62.8z M17.9,4.8
                    c-1.4,0-2.6,1.1-2.6,2.6v51c0,0.5,0.4,0.7,0.5,0.8c0.2,0.1,0.6,0.2,1-0.1l11.6-10.2c2.2-1.7,5.2-1.7,7.4,0L36,49l11.2,9.9
                    c0.4,0.3,0.8,0.2,1,0.1c0.2-0.1,0.5-0.3,0.5-0.8V7.3c0-1.4-1.1-2.6-2.6-2.6H17.9z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblcourse','_coursetype','Recorded'); ?></span>
                <span class="count-name">Recorded Courses</span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card-counter info">
                <svg fill="white" width="52" style="margin-left:10px" height="52" version="1.1" id="lni_lni-bookmark" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                  y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <g>
                  <path d="M16.2,62.8c-0.6,0-1.2-0.1-1.8-0.4c-1.6-0.7-2.6-2.3-2.6-4v-51c0-3.3,2.7-6.1,6.1-6.1h28.3c3.3,0,6.1,2.7,6.1,6.1v50.9
                    c0,1.7-1,3.3-2.6,4c-1.6,0.7-3.4,0.4-4.7-0.7l-11.4-10c-0.9-0.6-2.1-0.6-3,0L19.1,61.6C18.3,62.4,17.2,62.8,16.2,62.8z M17.9,4.8
                    c-1.4,0-2.6,1.1-2.6,2.6v51c0,0.5,0.4,0.7,0.5,0.8c0.2,0.1,0.6,0.2,1-0.1l11.6-10.2c2.2-1.7,5.2-1.7,7.4,0L36,49l11.2,9.9
                    c0.4,0.3,0.8,0.2,1,0.1c0.2-0.1,0.5-0.3,0.5-0.8V7.3c0-1.4-1.1-2.6-2.6-2.6H17.9z"/>
                </g>
                </svg>
                <span class="count-numbers"><?php echo _getdashtotal('tblcourse','_coursetype','Live'); ?></span>
                <span class="count-name">Live Courses</span>
              </div>
            </div>
            <a href="manage-course" style="margin-left: 20px;margin-top:10px">Manage Courses&nbsp;&nbsp;<svg fill="blue" style="width: 13px;margin-top:-3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"/></svg></a>
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
<script src="../assets/js/todolist.js"></script>

</html>