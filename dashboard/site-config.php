<?php

session_start();

if(!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn'] || $_SESSION['isLoggedIn'] == ''){
    echo "<script>";
    echo "window.location.href = 'login'";
    echo "</script>";
}else{
    if($_SESSION['userVerify'] != 'true'){
        echo "<script>";
        echo "window.location.href = 'verify'";
        echo "</script>";
    }
}

if(isset($_SESSION['forgot_success']) || !isset($_SESSION['forgot_success'])){
    $_SESSION['forgot_success'] = false;
  }

require('../includes/_functions.php'); 

if(isset($_POST['submit'])){
    $sitetitle = $_POST['sitetitle'];
    $siteemail = $_POST['siteemail'];
    $timezone = $_POST['timezone'];
    $header = $_POST['header'];
    $css = $_POST['css'];
    $logonewfile = null;
    $reslogonewfile = null;
    $faviconnewfile = null;
    if($_FILES["logo"]["name"] != ''){
        $logofile=$_FILES["logo"]["name"];
        $extension = substr($logofile,strlen($logofile)-4,strlen($logofile));
        $allowed_extensions = array(".jpg",".jpeg",".png",".gif",".webp");
        if(!in_array($extension,$allowed_extensions)){
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        }
        else{
            $logonewfile=md5($logofile).$extension;
            move_uploaded_file($_FILES["logo"]["tmp_name"],"../uploads/images/".$logonewfile);
        }
    }
    if($_FILES["reslogo"]["name"] != ''){
        $reslogofile=$_FILES["reslogo"]["name"];
        $extension = substr($reslogofile,strlen($reslogofile)-4,strlen($reslogofile));
        $allowed_extensions = array(".jpg",".jpeg",".png",".gif");
        if(!in_array($extension,$allowed_extensions)){
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        }
        else{
            $reslogonewfile=md5($reslogofile).$extension;
            move_uploaded_file($_FILES["reslogo"]["tmp_name"],"../uploads/images/".$reslogonewfile);
        }
    }
    if($_FILES["favicon"]["name"] != ''){
        $faviconfile=$_FILES["favicon"]["name"];
        $extension = substr($faviconfile,strlen($faviconfile)-4,strlen($faviconfile));
        $allowed_extensions = array(".jpg",".jpeg",".png",".gif");
        if(!in_array($extension,$allowed_extensions)){
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        }
        else{
            $faviconnewfile=md5($faviconfile).$extension;
            move_uploaded_file($_FILES["favicon"]["tmp_name"],"../uploads/images/".$faviconnewfile);
        }
    }
    _savesiteconfig($sitetitle,$siteemail,$timezone,$header,$css,$logonewfile,$reslogonewfile,$faviconnewfile);
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Site Config | <?php echo _siteconfig('_sitetitle'); ?></title>
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
        <?php if($_SESSION['forgot_success']){ ?>
            <div id="liveAlertPlaceholder">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Message Sent!</strong> message sent successfully.
                </div>
            </div>
            <?php } ?>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Site Configuration</h4>
                  <p class="card-description">
                  Site settings are the settings for a specific website within your Site. If you'd like to change settings for your Site overall, navigate to the Settings tab in the control panel.
                  From site settings, you’ll be able to configure the default settings, edit your footer, add header and background images, and more. 
                  </p>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col">
                                <label for="formFile" class="form-label">Site Title</label>
                                <input type="text" class="form-control" placeholder="Site Title" aria-label="site title" value="<?php echo _siteconfig('_sitetitle'); ?>" name="sitetitle" required>
                            </div>
                            <div class="col">
                                <label for="formFile" class="form-label">Site Email</label>
                                <input type="email" class="form-control" placeholder="Site Email" aria-label="site email" value="<?php echo _siteconfig('_siteemail'); ?>" name="siteemail" required>
                            </div>
                            <div class="col">
                                <label for="formFile" class="form-label">Select Timezone</label>
                                <select  class="form-control form-control-lg" name="timezone">
                                    <option>Select Timezone</option>
                                   <option value="<?php echo  _siteconfig('_timezone');?>" selected><?php echo _siteconfig('_timezone');?></option>
                                    <option value="Etc/GMT+12">(GMT-12:00) International Date Line West</option>
                                    <option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
                                    <option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
                                    <option value="US/Alaska">(GMT-09:00) Alaska</option>
                                    <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option value="America/Tijuana">(GMT-08:00) Tijuana, Baja California</option>
                                    <option value="US/Arizona">(GMT-07:00) Arizona</option>
                                    <option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option value="US/Mountain">(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option value="America/Managua">(GMT-06:00) Central America</option>
                                    <option value="US/Central">(GMT-06:00) Central Time (US & Canada)</option>
                                    <option value="America/Mexico_City">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option value="Canada/Saskatchewan">(GMT-06:00) Saskatchewan</option>
                                    <option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option value="US/Eastern">(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option value="US/East-Indiana">(GMT-05:00) Indiana (East)</option>
                                    <option value="Canada/Atlantic">(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option value="America/Caracas">(GMT-04:00) Caracas, La Paz</option>
                                    <option value="America/Manaus">(GMT-04:00) Manaus</option>
                                    <option value="America/Santiago">(GMT-04:00) Santiago</option>
                                    <option value="Canada/Newfoundland">(GMT-03:30) Newfoundland</option>
                                    <option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                                    <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires, Georgetown</option>
                                    <option value="America/Godthab">(GMT-03:00) Greenland</option>
                                    <option value="America/Montevideo">(GMT-03:00) Montevideo</option>
                                    <option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
                                    <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
                                    <option value="Atlantic/Azores">(GMT-01:00) Azores</option>
                                    <option value="Africa/Casablanca">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                    <option value="Etc/Greenwich">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="Europe/Sarajevo">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option value="Africa/Lagos">(GMT+01:00) West Central Africa</option>
                                    <option value="Asia/Amman">(GMT+02:00) Amman</option>
                                    <option value="Europe/Athens">(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                    <option value="Asia/Beirut">(GMT+02:00) Beirut</option>
                                    <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                                    <option value="Africa/Harare">(GMT+02:00) Harare, Pretoria</option>
                                    <option value="Europe/Helsinki">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                                    <option value="Europe/Minsk">(GMT+02:00) Minsk</option>
                                    <option value="Africa/Windhoek">(GMT+02:00) Windhoek</option>
                                    <option value="Asia/Kuwait">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                    <option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option value="Africa/Nairobi">(GMT+03:00) Nairobi</option>
                                    <option value="Asia/Tbilisi">(GMT+03:00) Tbilisi</option>
                                    <option value="Asia/Tehran">(GMT+03:30) Tehran</option>
                                    <option value="Asia/Muscat">(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option value="Asia/Baku">(GMT+04:00) Baku</option>
                                    <option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                                    <option value="Asia/Kabul">(GMT+04:30) Kabul</option>
                                    <option value="Asia/Yekaterinburg">(GMT+05:00) Yekaterinburg</option>
                                    <option value="Asia/Karachi">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                    <option value="Asia/Calcutta">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <option value="Asia/Calcutta">(GMT+05:30) Sri Jayawardenapura</option>
                                    <option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                                    <option value="Asia/Almaty">(GMT+06:00) Almaty, Novosibirsk</option>
                                    <option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                                    <option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
                                    <option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
                                    <option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option value="Asia/Kuala_Lumpur">(GMT+08:00) Kuala Lumpur, Singapore</option>
                                    <option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option value="Australia/Perth">(GMT+08:00) Perth</option>
                                    <option value="Asia/Taipei">(GMT+08:00) Taipei</option>
                                    <option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option value="Asia/Seoul">(GMT+09:00) Seoul</option>
                                    <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                                    <option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
                                    <option value="Australia/Darwin">(GMT+09:30) Darwin</option>
                                    <option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
                                    <option value="Australia/Canberra">(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                    <option value="Australia/Hobart">(GMT+10:00) Hobart</option>
                                    <option value="Pacific/Guam">(GMT+10:00) Guam, Port Moresby</option>
                                    <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
                                    <option value="Asia/Magadan">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                    <option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
                                    <option value="Pacific/Fiji">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col">
                                <label for="formFile" class="form-label">Site Logo</label>
                                <input class="form-control" name="logo" type="file" id="formFile">
                                <img style="border: 2px solid #EFEFEF; margin-top: 10px; padding: 10px;border-radius: 12px; width: 280px; height: 100px" src="../uploads/images/<?php echo _siteconfig('_sitelogo'); ?>" alt="sitelogo">
                            </div>
                            <div class="col">
                                <label for="formFile" class="form-label">Site Logo (Responsive)</label>
                                <input class="form-control" name="reslogo" type="file" id="formFile">
                                <img style="border: 2px solid #EFEFEF; margin-top: 10px; padding: 10px;border-radius: 12px; width: 180px; height: 100px" src="../uploads/images/<?php echo _siteconfig('_sitereslogo'); ?>" alt="sitelogo">
                            </div>
                            <div class="col">
                                <label for="formFile" class="form-label">Site Favicon</label>
                                <input class="form-control" name="favicon" type="file" id="formFile">
                                <img style="border: 2px solid #EFEFEF; margin-top: 10px; padding: 10px;border-radius: 12px; width: 180px; height: 100px" src="../uploads/images/<?php echo _siteconfig('_favicon'); ?>" alt="sitelogo">
                            </div>
                        </div>
                </div>    
            </div>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Header Configuration</h4>
                    <p class="card-description">
                    Site settings are the settings for a specific website within your Site. If you'd like to change settings for your Site overall, navigate to the Settings tab in the control panel.
                    From site settings, you’ll be able to configure the default settings, edit your footer, add header and background images, and more. 
                    </p>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col-6">
                                <label for="exampleFormControlTextarea1" class="form-label">Header Codes</label>
                                <textarea class="form-control" name="header" id="exampleFormControlTextarea1" rows="6"><?php echo _siteconfig('_customheader'); ?></textarea>
                            </div>
                            <div class="col-6">
                                <label for="exampleFormControlTextarea1" class="form-label">Custom CSS</label>
                                <textarea class="form-control" name="css" id="exampleFormControlTextarea1" rows="6"><?php echo _siteconfig('_customcss'); ?></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="margin-top: 30px;">
                            <button type="submit" name="submit" style="width: 180px;margin-left: -10px" class="btn btn-primary"><i class="mdi mdi-content-save"></i>&nbsp;&nbsp;Save Settings</button>
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