<?php

// Dashboard Functions 
function _getdashtotal($param, $active, $status)
{
    require('_config.php');
    $sql = "SELECT * FROM `$param` WHERE `$active` = '$status'";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);
    return $count;
}

/* Auth Functions */
function _login($userpassword, $useremail)
{
    require('_config.php');
    require('_alert.php');
    if ($userpassword && $useremail != '') {
        $enc_password = md5($userpassword);
        $sql = "SELECT * FROM `tblusers` WHERE `_userstatus` = 'true' AND `_userpassword` = '$enc_password' AND `_useremail` = '$useremail' OR `_userphone` = '$useremail'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $count = mysqli_num_rows($query);
            if ($count >= 1) {
                foreach ($query as $data) {
                    $usertype = $data['_usertype'];
                    $userverify = $data['_userverify'];
                    $userid = $data['_id'];
                    $useremail = $data['_useremail'];
                    $userphone = $data['_userphone'];
                    $userpass = $data['_userpassword'];
                }
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['userEmailId'] = $useremail;
                $_SESSION['userPhoneNo'] = $userphone;
                $_SESSION['userPassword'] = $userpass;
                $_SESSION['userType'] = $usertype;
                $_SESSION['userVerify'] = $userverify;
                $_SESSION['userId'] = $userid;
                $alert = new PHPAlert();
                $alert->success("Login Successfull");
                echo "<script>";
                echo "window.location.href = ''";
                echo "</script>";
            } else {
                $alert = new PHPAlert();
                $alert->warn("Login Failed");
            }
        } else {
            $alert = new PHPAlert();
            $alert->warn("Something Went Wrong");
        }
    } else {
        $alert = new PHPAlert();
        $alert->warn("All Feilds are Required");
    }
}

function _signup($userpassword, $useremail, $username, $userphone)
{
    require('_config.php');
    require('_alert.php');
    if ($userpassword && $useremail != '') {
        $enc_password = md5($userpassword);
        $userotp = rand(1111, 9999);
        $sql = "SELECT * FROM `tblusers` WHERE `_useremail` = '$useremail' OR `_userphone` = '$userphone'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $count = mysqli_num_rows($query);
            if ($count > 0) {
                $alert = new PHPAlert();
                $alert->warn("User Already Exists");
            } else {
                $sql = "INSERT INTO `tblusers`(`_username`, `_useremail`, `_userphone`, `_usertype`, `_userstatus`, `_userpassword`, `_userotp`, `_userverify`) VALUES ('$username','$useremail', '$userphone','0', 'true', '$enc_password', '$userotp', 'false')";

                $query = mysqli_query($conn, $sql);
                if ($query) {
                    $_SESSION['temp_username'] = $username;
                    $_SESSION['temp_phone'] = $userphone;
                    _sendotp($userotp, $userphone, $useremail);
                }
            }
        }
    }
}

function _forgetpass($useremail, $userphone)
{
    require('_config.php');
    require('_alert.php');
    $userpass = rand(11111111, 99999999);
    $enc_pass = md5($userpass);
    $sql = "SELECT * FROM `tblusers` WHERE `_useremail` = '$useremail' AND `_userphone` = '$userphone'";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);
    if ($count > 0) {
        $sql = "UPDATE `tblusers` SET `_userpassword`='$enc_pass' WHERE `_useremail` = '$useremail'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $subject = 'Password Changed';
            $message = "Password : $userpass (Your New Password)";
            $sendmail = "Password : $userpass (Your New Password)";
            _notifyuser($useremail, $userphone, $sendmail, $message, $subject);
        }
    } else {
        $alert = new PHPAlert();
        $alert->warn("Incorrect Credentials");
    }
}

function _logout()
{
    // Initialize the session
    session_start();

    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to login page
    header("location: login");
    exit;
}

function _verifyotp($verifyotp)
{
    require('_alert.php');
    require('_config.php');
    $useremail = $_SESSION['userEmailId'];
    $sql = "SELECT * FROM `tblusers` WHERE `_useremail` = '$useremail'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            $otp = $data['_userotp'];
        }
        if ($verifyotp == $otp) {
            $sql = "UPDATE `tblusers` SET `_userverify` = 'true' WHERE `_useremail` = '$useremail'";
            $query = mysqli_query($conn, $sql);
            if ($query) {
                $_SESSION['signup_success'] = true;
                $sql = "SELECT * FROM `tblemailtemplates`";
                $query = mysqli_query($conn, $sql);
                foreach ($query as $data) {
                    $template = $data['_signuptemplate'];
                }
                $variables = array();
                $variables['name'] = $_SESSION['temp_username'];
                $variables['companyname'] = _siteconfig('_sitetitle');
                $sendmail = _usetemplate($template, $variables);
                $subject = "Account Created Successfully";
                $userphone = $_SESSION['temp_phone'];
                $message = 'Thank you for creating account with ' . _siteconfig('_sitetitle') . '. Kindy Login to Continue';
                _notifyuser($useremail, $userphone, $sendmail, $message, $subject);
                echo "<script>";
                echo "window.location.href = 'login'";
                echo "</script>";
            } else {
                $alert = new PHPAlert();
                $alert->warn("Something Went Wrong");
            }
        } else {
            $alert = new PHPAlert();
            $alert->warn("Verification Failed");
        }
    }
}

function _sendotp($otp, $userphone, $useremail)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblsmsconfig` WHERE `_supplierstatus` = 'true'";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);
    if ($count > 0) {
        foreach ($query as $data) {
            $baseurl = $data['_baseurl'];
            $apikey = $data['_apikey'];
        }
        $fields = array(
            "variables_values" => "$otp",
            "route" => "otp",
            "numbers" => "$userphone",
        );

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $baseurl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($fields),
                CURLOPT_HTTPHEADER => array(
                    "authorization: $apikey",
                    "accept: */*",
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            )
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response);
            $sts = $data->return;
            if ($sts == false) {
                $alert = new PHPAlert();
                $alert->warn("OTP Failed");
            } else {
                $_SESSION['userEmailId'] = $useremail;
                $_SESSION['userEmailPhone'] = $userphone;
                echo "<script>";
                echo "window.location.href = 'verify'";
                echo "</script>";
            }
        }
    } else {
        $sql = "UPDATE `tblusers` SET `_userverify` = 'true' WHERE `_useremail` = '$useremail'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $_SESSION['signup_success'] = true;
            echo "<script>";
            echo "window.location.href = 'login'";
            echo "</script>";
        } else {
            $alert = new PHPAlert();
            $alert->warn("Verification Failed 2");
        }
    }
}

function _resendtop()
{
    session_start();
    require('_config.php');
    $userotp = rand(1111, 9999);
    $useremail = $_SESSION['userEmailId'];
    $userphone = $_SESSION['userEmailPhone'];
    $sql = "UPDATE `tblusers` SET `_userotp` = $userotp WHERE `_useremail` = '$useremail'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        _sendotp($userotp, $userphone, $useremail);
    }
}

function _install($dbhost, $dbname, $dbpass, $dbuser, $siteurl, $username, $userpassword, $useremail)
{
    require('_alert.php');
    ini_set('display_errors', 1);
    $temp_conn = new mysqli($dbhost, $dbuser, $dbpass);
    $enc_password = md5($userpassword);
    if ($temp_conn->connect_errno) {
        $alert = new PHPAlert();
        $alert->warn("Database Connection Failed");
        exit();
    } else {
        $db_tables = array(
            'db_server' => $dbhost,
            'db_username' => $dbuser,
            'db_password' => $dbpass,
            'db_name' => $dbname,
            'site_url' => $siteurl
        );

        $db = "CREATE DATABASE IF NOT EXISTS $dbname";

        if ($temp_conn->query($db)) {
            $temp_conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

            $admin_table = "CREATE TABLE IF NOT EXISTS `tblusers` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_username` varchar(255) NULL,
                `_useremail` varchar(255) NULL,
                `_userphone` varchar(255) NULL,
                `_usersite` varchar(255) NULL,
                `_usermembership` varchar(255) NULL,
                `_usermemstart` datetime NULL,
                `_usermemsleft` varchar(255) NULL,
                `_userlongitude` varchar(50) NULL,
                `_userlatitude` varchar(50) NULL,
                `_userbio` varchar(500) NULL,
                `_userage` varchar(10) NULL,
                `_userlocation` varchar(100) NULL,
                `_userstate` varchar(50) NULL,
                `_userpin` varchar(50) NULL,
                `_userdp` varchar(50) NULL,
                `_usertype` int(11) NULL,
                `_userstatus` varchar(50) NULL,
                `_userpassword` varchar(255) NULL,
                `_userotp` int(100) NULL,
                `_userverify` varchar(50) NULL,
                `Creation_at_Date` date NOT NULL DEFAULT current_timestamp(),
                `CreationDate` datetime NOT NULL DEFAULT current_timestamp(),
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $sms_config = "CREATE TABLE IF NOT EXISTS `tblsmsconfig` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_suppliername` varchar(50) NOT NULL,
                `_apikey` varchar(100) NOT NULL,
                `_baseurl` varchar(100) NOT NULL,
                `_supplierstatus` varchar(50) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT current_timestamp(),
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $email_config = "CREATE TABLE IF NOT EXISTS `tblemailconfig` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_hostname` varchar(50) NOT NULL,
                `_hostport` varchar(50) NOT NULL,
                `_smtpauth` varchar(50) NOT NULL,
                `_emailaddress` varchar(100) NOT NULL,
                `_emailpassword` varchar(100) NOT NULL,
                `_sendername` varchar(100) NOT NULL,
                `_supplierstatus` varchar(50) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT current_timestamp(),
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $site_config = "CREATE TABLE IF NOT EXISTS `tblsiteconfig` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_sitetitle` varchar(50) NULL,
                `_siteemail` varchar(50) NULL,
                `_timezone` varchar(50) NULL,
                `_customheader` text NULL,
                `_customfooter` text NULL,
                `_customcss` text NULL,
                `_sitelogo` varchar(100) NULL,
                `_sitereslogo` varchar(100) NULL,
                `_favicon` varchar(100) NULL,
                `CreationDate` datetime NOT NULL DEFAULT current_timestamp(),
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $payment_config = "CREATE TABLE IF NOT EXISTS `tblpaymentconfig` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_suppliername` varchar(50) NOT NULL,
                `_apikey` varchar(100) NOT NULL,
                `_companyname` varchar(100) NOT NULL,
                `_supplierstatus` varchar(50) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT current_timestamp(),
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $tickets_table = "CREATE TABLE IF NOT EXISTS `tbltickets` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_title` varchar(255) NOT NULL,
                `_message` text NOT NULL,
                `_image` varchar(255) NULL,
                `_category` varchar(255) NOT NULL,
                `_subcategory` varchar(255) NOT NULL,
                `_useremail` varchar(255) NOT NULL,
                `_status` enum('open','closed','pending','resolved') NOT NULL DEFAULT 'open',
                `Creation_at_Date` date NOT NULL DEFAULT current_timestamp(),
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $ticket_comment = "CREATE TABLE IF NOT EXISTS `tblticketres` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_ticket_id` int(11) NOT NULL,
                `_message` text NOT NULL,
                `_image` varchar(255) NOT NULL,
                `_useremail` varchar(255) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $contact_table = "CREATE TABLE IF NOT EXISTS `tblcontact` (
                `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `FullName` varchar(50) NOT NULL,
                `EmailId` varchar(100) NOT NULL,
                `PhoneNo` varchar(20) NOT NULL,
                `Message` varchar(250) NOT NULL,
                `PostedAt` datetime NOT NULL DEFAULT current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $category_table = "CREATE TABLE IF NOT EXISTS `tblcategory` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_categoryname` varchar(50) NOT NULL,
                `_categoryDescription` varchar(100) NOT NULL,
                `_status` varchar(20) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $subcategory_table = "CREATE TABLE IF NOT EXISTS `tblsubcategory` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_subcategoryname` varchar(50) NOT NULL,
                `_categoryid` varchar(20) NOT NULL,
                `_subcategorydesc` varchar(100) NOT NULL,
                `_status` varchar(20) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $blog_table = "CREATE TABLE IF NOT EXISTS `tblblog` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_blogtitle` varchar(255) NOT NULL,
                `_parmalink` varchar(255) NOT NULL,
                `_blogdesc` text NOT NULL,
                `_blogcategory` varchar(50) NOT NULL,
                `_blogsubcategory` varchar(50) NOT NULL,
                `_blogmetadesc` varchar(250) NOT NULL,
                `_blogimg` varchar(100) NOT NULL,
                `_userid` varchar(50) NOT NULL,
                `_status` varchar(20) NOT NULL,
                `Creation_at_Date` date NOT NULL DEFAULT current_timestamp(),
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $currency_table = "CREATE TABLE IF NOT EXISTS `tblcurrency` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_basecurrency` varchar(255) NOT NULL,
                `_conversioncurrency` text NOT NULL,
                `_price` varchar(255) NULL,
                `_status` varchar(50) NOT NULL DEFAULT 'open',
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $tax_table = "CREATE TABLE IF NOT EXISTS `tbltaxes` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_taxname` varchar(255) NOT NULL,
                `_taxtype` text NOT NULL,
                `_taxamount` varchar(255) NULL,
                `_taxcurrency` varchar(255) NULL,
                `_status` varchar(50) NOT NULL DEFAULT 'true',
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $payment_trans = "CREATE TABLE IF NOT EXISTS `tblpayment` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_useremail` varchar(255) NOT NULL,
                `_amount` varchar(255) NULL,
                `_currency` varchar(255) NOT NULL,
                `_status` varchar(255) NOT NULL,
                `_producttitle` varchar(100) NOT NULL,
                `_productid` varchar(55) NOT NULL,
                `_producttype` varchar(55) NOT NULL,
                `_couponcode` varchar(255) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $coupon_table = "CREATE TABLE IF NOT EXISTS `tblcoupon` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_couponname` varchar(255) NOT NULL,
                `_coupontype` text NOT NULL,
                `_couponamount` varchar(255) NULL,
                `_couponcurrency` varchar(255) NULL,
                `_couponcondition` varchar(255) NULL,
                `_couponprod` varchar(255) NULL,
                `_conamount` varchar(255) NULL,
                `_maxusage` varchar(255) NOT NULL,
                `_totaluse` varchar(255) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $coupon_trans = "CREATE TABLE IF NOT EXISTS `tblcoupontrans` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_couponname` varchar(255) NOT NULL,
                `_couponamount` varchar(255) NULL,
                `_couponcurrency` varchar(255) NULL,
                `_couponstatus` varchar(255) NULL,
                `_useremail` varchar(255) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $membership_table = "CREATE TABLE IF NOT EXISTS `tblmembership` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_membershipname` varchar(100) NOT NULL,
                `_membershipdesc` varchar(500) NOT NULL,
                `_price` varchar(55) NOT NULL,
                `_benefit` varchar(55) NOT NULL, 
                `_benefittype` varchar(55) NOT NULL, 
                `_duration` varchar(55) NOT NULL,
                `_status` varchar(255) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $templates = "CREATE TABLE IF NOT EXISTS `tblemailtemplates` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_purchasetemplate` text NOT NULL,
                `_remindertemplate` text NOT NULL,
                `_lecturetemplate` text NOT NULL,
                `_signuptemplate` text NOT NULL,
                `_canceltemplate` text NOT NULL,
                `_paymenttemplate` text NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";


            $invoice = "CREATE TABLE IF NOT EXISTS `tblinvoice` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_clientname` varchar(255) NOT NULL,
                `_clientemail` varchar(255) NOT NULL,
                `_clientnumber` varchar(255) NOT NULL, 
                `_clientaddress` varchar(255) NOT NULL, 
                `_paymentstatus` varchar(255) NOT NULL,
                `_refno` varchar(255) NOT NULL,
                `_invoicenote` text NOT NULL,
                `_duedate` varchar(255) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";


            $invoiceitems = "CREATE TABLE IF NOT EXISTS `tblinvoiceitems` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_invoiceno` varchar(55) NOT NULL,
                `_productname` varchar(55) NOT NULL,
                `_productquantity` varchar(55) NOT NULL, 
                `_productamount` varchar(55) NOT NULL, 
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";


            $course = "CREATE TABLE IF NOT EXISTS `tblcourse` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_coursename` text NOT NULL,
                `_coursedescription` text NOT NULL,
                `_whatlearn` text NOT NULL,
                `_requirements` text NOT NULL,
                `_eligibilitycriteria` text NOT NULL,
                `_capacity` varchar(50) NOT NULL,
                `_enrollstatus` varchar(50) NOT NULL,
                `_thumbnail` varchar(100) NOT NULL,
                `_banner` varchar(100) NOT NULL,
                `_pricing` varchar(50) NOT NULL,
                `_status` varchar(50) NOT NULL,
                `_teacheremailid` varchar(50) NOT NULL,
                `_categoryid` varchar(50) NOT NULL,
                `_subcategoryid` varchar(50) NOT NULL,
                `_coursetype` varchar(50) NOT NULL,
                `_coursechannel` varchar(50) NOT NULL,
                `_courselevel` varchar(50) NOT NULL,
                `_evuluationlink` varchar(50) NOT NULL,
                `_startdate` varchar(255)  NOT NULL,
                `_enddate` varchar(255)  NOT NULL,
                `_discountprice` varchar(50)  NOT NULL,
                `Creation_at_Date` date NOT NULL DEFAULT current_timestamp(),
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $lessondb = "CREATE TABLE IF NOT EXISTS `tbllessons` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_courseid` varchar(55) NOT NULL,
                `_lessonname` text NOT NULL,
                `_lessontype` varchar(55) NOT NULL,
                `_lessonurl` varchar(55) NOT NULL,
                `_recordedfilename` varchar(55) NOT NULL,
                `_lessondescription` text NOT NULL,
                `_status` varchar(55) NOT NULL,
                `_availablity` varchar(55) NOT NULL,
                `Creation_at_Date` date NOT NULL DEFAULT current_timestamp(),
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $slidesdb = "CREATE TABLE IF NOT EXISTS `tblslides` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_courseid` varchar(55) NOT NULL,
                `_slideurl` varchar(55) NOT NULL,
                `_caption` varchar(55) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";



            $attachmentsDB = "CREATE TABLE IF NOT EXISTS `tblattachements` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_lessonid` varchar(55) NOT NULL,
                `_attachementurl` varchar(55) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;";





            $tables = [$admin_table, $sms_config, $email_config, $site_config, $payment_config, $tickets_table, $ticket_comment, $contact_table, $category_table, $subcategory_table, $blog_table, $currency_table, $tax_table, $payment_trans, $coupon_table, $coupon_trans, $membership_table, $templates, $invoice, $invoiceitems, $course, $lessondb, $slidesdb,$attachmentsDB];

            foreach ($tables as $k => $sql) {
                $query = @$temp_conn->query($sql);

                if (!$query) {
                    $errors[] = "Table $k : Creation failed ($temp_conn->error)";
                } else {
                    $errors[] = "Table $k : Creation done";
                    $creation_done = true;
                }
            }
            if ($creation_done) {
                $admin_data = "INSERT INTO `tblusers` (`_username`, `_useremail`,  `_userphone`, `_usertype`, `_userstatus`, `_userpassword`,`_userverify`) VALUES ('$username', '$useremail', '', 2, 'true', '$enc_password','true');";

                $sms_data = "INSERT INTO `tblsmsconfig`(`_suppliername`, `_apikey`, `_baseurl`, `_supplierstatus`) VALUES ('Fast2SMS','maeS4bc5gM17qo0FwszOEAx62JND3IiHdfQBtl8XWLZ9rCjVTYOJlgtFLzNqZ7uYj830XWm6sQbM2KIR', 'https://www.fast2sms.com/dev/bulkV2', 'true')";

                $email_data = "INSERT INTO `tblemailconfig`(`_hostname`, `_hostport`, `_smtpauth`, `_emailaddress`, `_emailpassword`, `_sendername`, `_supplierstatus`) VALUES ('mail.adenwalla.in', '465', 'true', 'info@adenwalla.in', 'Juned@786juned', 'Adenwalla Infotech', 'true')";

                $site_data = "INSERT INTO `tblsiteconfig`(`_sitetitle`, `_siteemail`, `_timezone`, `_sitelogo`, `_sitereslogo`, `_favicon`) VALUES ('Site Title', 'info@yoursite.com', 'Asia/Calcutta', 'uploadimage.png', 'uploadimage.png', 'uploadimage.png')";

                $payment_data = "INSERT INTO `tblpaymentconfig`(`_suppliername`, `_apikey`, `_companyname`, `_supplierstatus`) VALUES ('Razorpay','12345678901234567890','Adenwalla & Co.','true')";

                $template_data = "INSERT INTO `tblemailtemplates`(`_purchasetemplate`, `_remindertemplate`, `_lecturetemplate`, `_signuptemplate`, `_canceltemplate`, `_paymenttemplate`) VALUES ('Your Html Code','Your Html Code','Your Html Code','Your Html Code','Your Html Code','Your Html Code')";

                $data = [$admin_data, $sms_data, $email_data, $site_data, $payment_data, $template_data];

                foreach ($data as $k => $sql) {
                    $query = @$temp_conn->query($sql);

                    if (!$query) {
                        $errors[] = "Table $k : Creation failed ($temp_conn->error)";
                        echo 'falied';
                    } else {
                        $errors[] = "Table $k : Creation done";
                        $creation_done = true;
                    }
                }
                if ($creation_done) {
                    $json = file_put_contents(__DIR__ . '/../_config.json', json_encode($db_tables));
                    if (!file_exists('.htaccess')) {
                        $content = "RewriteEngine On" . "\n";
                        $content .= "RewriteRule ^([^/\.]+)/([^/\.]+)?$ post.php?type=$1&post=$2" . "\n";
                        $content .= "RewriteCond %{REQUEST_FILENAME} !-f" . "\n";
                        $content .= "RewriteRule ^([^\.]+)$ $1.php [NC,L]" . "\n";
                        $content .= "ErrorDocument 404 /404.php" . "\n";
                        file_put_contents(__DIR__ . '/../.htaccess', $content);
                    }
                    // $delete_install = unlink(__DIR__.'/../install.php');
                    if ($json) {
                        $alert = new PHPAlert();
                        $alert->success("Installation Success");
                    }
                }
            } else {
                $alert = new PHPAlert();
                $alert->warn("Installation Failed");
            }
        }
    }
}

/* User Functions */

function _createuser($username, $useremail, $usertype, $userphone, $isactive, $isverified, $notify)
{
    require('_config.php');
    require('_alert.php');
    if ($useremail != '' && $username != '' && $usertype != '' && $userphone != '') {

        $userotp = rand(1111, 9999);
        $subject = "Account Created";
        $message = "Account Created Successfully";
        $sql = "SELECT * FROM `tblusers` WHERE `_useremail` = '$useremail' OR `_userphone` = '$userphone'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $count = mysqli_num_rows($query);
            if ($count > 0) {
                $alert = new PHPAlert();
                $alert->warn("User Already Exists");
            } else {
                $sql = "INSERT INTO `tblusers`(`_username`, `_useremail`, `_userphone`, `_usertype`, `_userstatus`, `_userotp`, `_userverify`) VALUES ('$username','$useremail', '$userphone', '$usertype', '$isactive', '$userotp', '$isverified')";

                $query = mysqli_query($conn, $sql);
                if ($query) {
                    if ($notify) {
                        $sql = "SELECT * FROM `tblemailtemplates`";
                        $query = mysqli_query($conn, $sql);
                        foreach ($query as $data) {
                            $template = $data['_signuptemplate'];
                        }
                        $variables = array();
                        $variables['name'] = $username;
                        $variables['companyname'] = _siteconfig('_sitetitle');
                        $sendmail = _usetemplate($template, $variables);
                        $message = 'Thank you for creating account with ' . _siteconfig('_sitetitle') . '. Kindy Login to Continue';
                        _notifyuser($useremail, $userphone, $sendmail, $message, $subject);
                        $alert = new PHPAlert();
                        $alert->success("User Created");
                    } else {
                        $alert = new PHPAlert();
                        $alert->success("User Created");
                    }
                }
            }
        }
    } else {
        $alert = new PHPAlert();
        $alert->warn("All Feilds are Required");
    }
}

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function _notifyuser($useremail = '', $userphone = '', $sendmail = '', $message = '', $subject = '')
{
    require('_config.php');
    if ($userphone != '') {
        $sql = "SELECT * FROM `tblsmsconfig` WHERE `_supplierstatus` = 'true'";
        $query = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($query);
        if ($count > 0) {
            foreach ($query as $data) {
                $baseurl = $data['_baseurl'];
                $apikey = $data['_apikey'];
            }

            $fields = array(
                "message" => $message,
                "sender_id" => "FSTSMS",
                "language" => "english",
                "route" => "v3",
                "numbers" => $userphone,
            );

            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => $baseurl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($fields),
                    CURLOPT_HTTPHEADER => array(
                        "authorization: $apikey",
                        "accept: */*",
                        "cache-control: no-cache",
                        "content-type: application/json"
                    ),
                )
            );

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $alert = new PHPAlert();
                $alert->warn("SMS not sent");
            } else {
                $_SESSION['template_success'] = true;
            }
        }
    }
    if ($useremail != '') {
        $sql = "SELECT * FROM `tblemailconfig` WHERE `_supplierstatus` = 'true'";
        $query = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($query);
        if ($count == 1) {
            require_once "../vendor/autoload.php";
            $mail = new PHPMailer(true); //Argument true in constructor enables exceptions
            //Set PHPMailer to use SMTP.
            $mail->isSMTP();
            foreach ($query as $data) {
                //Enable SMTP debugging.
                // $mail->SMTPDebug = 10;                                       
                //Set SMTP host name                          
                $mail->Host = $data['_hostname'];
                //Set this to true if SMTP host requires authentication to send email
                $mail->SMTPAuth = $data['_smtpauth'];
                //Provide username and password     
                $mail->Username = $data['_emailaddress'];
                $mail->Password = $data['_emailpassword'];
                //If SMTP requires TLS encryption then set it
                $mail->SMTPSecure = "ssl";
                //Set TCP port to connect to
                $mail->Port = $data['_hostport'];

                $mail->From = $data['_emailaddress'];
                $mail->FromName = $data['_sendername'];
                //Address to which recipient will reply
                $mail->addReplyTo($data['_emailaddress'], "Reply");
            }
            //To address and namS
            $mail->addAddress($useremail); //Recipient name is optional

            $mail->isHTML(true);

            $mail->Subject = $subject;
            $mail->Body = $sendmail;
            $mail->IsHTML(true);
            if ($mail->send()) {
                $_SESSION['send_mail'] = true;
            } else {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }
    }
}

function _getuser($username = '', $usertype = '', $createdat = '', $limit = '', $startfrom = '')
{


    require('_config.php');
    if ($usertype != '') {
        $sql = "SELECT * FROM `tblusers` WHERE `_usertype` = '$usertype'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            foreach ($query as $data) { ?>
                <tr>
                    <td><?php echo $data['_username']; ?></td>
                    <td><?php echo $data['_useremail']; ?></td>
                    <td>
                        <?php
                if ($data['_usertype'] == 0) { ?>
                            <span>Student</span>
                        <?php }
                if ($data['_usertype'] == 1) { ?>
                            <span>Teacher</span>
                        <?php }
                if ($data['_usertype'] == 2) { ?>
                            <span>Site Admin</span>
                        <?php } ?>
                    </td>
                    <td>
                        <label class="checkbox-inline form-switch">
                            <?php
                if ($data['_userstatus'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userstatus'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <label class="checkbox-inline">
                            <?php
                if ($data['_userverify'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userverify'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <?php echo date("F j, Y", strtotime($data['CreationDate'])); ?>
                    </td>
                    <td>
                        <?php
                if (strtotime($data['UpdationDate']) == '') {
                    echo "Not Updated Yet";
                } else {
                    echo date("M j, Y", strtotime($data['UpdationDate']));
                }
                        ?>
                    </td>
                    <td><a href="edit-user?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                        <a href='manage-users?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                    </td>
                </tr>
            <?php }
        }
    }
    if ($username != '') {
        $sql = "SELECT * FROM `tblusers` WHERE `_useremail` LIKE '%$username%'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            foreach ($query as $data) { ?>
                <tr>
                    <td><?php echo $data['_username']; ?></td>
                    <td><?php echo $data['_useremail']; ?></td>
                    <td>
                        <?php
                if ($data['_usertype'] == 0) { ?>
                            <span>Student</span>
                        <?php }
                if ($data['_usertype'] == 1) { ?>
                            <span>Teacher</span>
                        <?php }
                if ($data['_usertype'] == 2) { ?>
                            <span>Site Admin</span>
                        <?php } ?>
                    </td>
                    <td>
                        <label class="checkbox-inline form-switch">
                            <?php
                if ($data['_userstatus'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userstatus'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <label class="checkbox-inline">
                            <?php
                if ($data['_userverify'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userverify'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <?php echo date("F j, Y", strtotime($data['CreationDate'])); ?>
                    </td>
                    <td>
                        <?php
                if (strtotime($data['UpdationDate']) == '') {
                    echo "Not Updated Yet";
                } else {
                    echo date("M j, Y", strtotime($data['UpdationDate']));
                }
                        ?>
                    </td>
                    <td><a href="edit-user?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                        <a href='manage-users?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                    </td>
                </tr>
            <?php }
        }
    }

    if ($createdat != '') {

        $sql = "SELECT * FROM `tblusers` WHERE `Creation_at_Date` LIKE '$createdat' ";      

        $query = mysqli_query($conn, $sql);

        
        if ($query) {
            foreach ($query as $data) { ?>
                <tr>
                    <td><?php echo $data['_username']; ?></td>
                    <td><?php echo $data['_useremail']; ?></td>
                    <td>
                        <?php
                if ($data['_usertype'] == 0) { ?>
                            <span>Student</span>
                        <?php }
                if ($data['_usertype'] == 1) { ?>
                            <span>Teacher</span>
                        <?php }
                if ($data['_usertype'] == 2) { ?>
                            <span>Site Admin</span>
                        <?php } ?>
                    </td>
                    <td>
                        <label class="checkbox-inline form-switch">
                            <?php
                if ($data['_userstatus'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userstatus'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <label class="checkbox-inline">
                            <?php
                if ($data['_userverify'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userverify'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <?php echo date("F j, Y", strtotime($data['CreationDate'])); ?>
                    </td>
                    <td>
                        <?php
                if (strtotime($data['UpdationDate']) == '') {
                    echo "Not Updated Yet";
                } else {
                    echo date("M j, Y", strtotime($data['UpdationDate']));
                }
                        ?>
                    </td>
                    <td><a href="edit-user?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                        <a href='manage-users?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                    </td>
                </tr>
            <?php }
        }
    } else if ($username == '' && $usertype == '' && $createdat == '') {
        $sql = "SELECT * FROM `tblusers` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            foreach ($query as $data) { ?>
                <tr>
                    <td><?php echo $data['_username']; ?></td>
                    <td><?php echo $data['_useremail']; ?></td>
                    <td>
                        <?php
                if ($data['_usertype'] == 0) { ?>
                            <span>Student</span>
                        <?php }
                if ($data['_usertype'] == 1) { ?>
                            <span>Teacher</span>
                        <?php }
                if ($data['_usertype'] == 2) { ?>
                            <span>Site Admin</span>
                        <?php } ?>
                    </td>
                    <td>
                        <label class="checkbox-inline form-switch">
                            <?php
                if ($data['_userstatus'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userstatus'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <label class="checkbox-inline">
                            <?php
                if ($data['_userverify'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
                if ($data['_userverify'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                                    ?>
                        </label>
                    </td>
                    <td>
                        <?php echo date("F j, Y", strtotime($data['CreationDate'])); ?>
                    </td>
                    <td>
                        <?php
                if (strtotime($data['UpdationDate']) == '') {
                    echo "Not Updated Yet";
                } else {
                    echo date("M j, Y", strtotime($data['UpdationDate']));
                }
                        ?>
                    </td>
                    <td><a href="edit-user?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                        <a href='manage-users?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                    </td>
                </tr>
            <?php }
        }
    }
}

function _deleteuser($id)
{
    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tblusers` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("User Deleted Permanently");
    }
}

function _updateuser($username, $useremail, $usertype, $userphone, $isactive, $isverified, $_id)
{
    require('_config.php');
    require('_alert.php');

    $sql = "UPDATE `tblusers` SET `_username`='$username' , `_useremail`='$useremail' , `_userphone`='$userphone' 
    , `_usertype`='$usertype' , `_userstatus`='$isactive' , `_userverify`='$isverified' WHERE `_id` = $_id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("User Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _updateProfile($username, $useremail, $userpassword, $userphone, $userage, $userbio, $location, $userpin, $country)
{
    require('_config.php');
    require('_alert.php');
    $email = $_SESSION['userEmailId'];
    $phone = $_SESSION['userPhoneNo'];
    $id = $_SESSION['userId'];
    if ($phone != $userphone && $email != $useremail) {
        $sql = "SELECT * FROM `tblusers` WHERE`_useremail` = '$useremail' AND `_userphone` = '$userphone'";
        $run = true;
    }
    if ($phone != $userphone) {
        $sql = "SELECT * FROM `tblusers` WHERE `_userphone` = '$userphone'";
        $run = true;
    }
    if ($email != $useremail) {
        $sql = "SELECT * FROM `tblusers` WHERE `_useremail` = '$useremail'";
        $run = true;
    }
    if ($phone == $userphone && $email == $useremail) {
        $run = false;
    }
    if ($run) {
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $count = mysqli_num_rows($query);
            if ($count > 0) {
                $alert = new PHPAlert();
                $alert->warn("Credential Already in use");
            } else {
                $password = $_SESSION['userPassword'];
                if ($userpassword == $password) {
                    $encpassword = $userpassword;
                    $sql = "UPDATE `tblusers` SET `_username`='$username',`_useremail`='$useremail',`_userphone`='$userphone',`_userbio`='$userbio',`_userage`='$userage',`_userlocation`='$location',`_userstate`='$country',`_userpin`='$userpin' WHERE `_id` = $id";
                } else {
                    $encpassword = md5($userpassword);
                    $sql = "UPDATE `tblusers` SET `_username`='$username',`_useremail`='$useremail',`_userphone`='$userphone',`_userbio`='$userbio',`_userage`='$userage',`_userlocation`='$location',`_userstate`='$country',`_userpin`='$userpin',`_userpassword`='$encpassword' WHERE `_id` = $id";
                }

                $query = mysqli_query($conn, $sql);
                if ($query) {
                    $alert = new PHPAlert();
                    $alert->success("Profile Updated");
                    $_SESSION['userEmailId'] = $useremail;
                    $_SESSION['userPhoneNo'] = $userphone;
                    $_SESSION['userPassword'] = $encpassword;
                } else {
                    $alert = new PHPAlert();
                    $alert->warn("Something went wrong");
                }
            }
        }
    } else {
        $password = $_SESSION['userPassword'];
        if ($userpassword == $password) {
            $encpassword = $userpassword;
            $sql = "UPDATE `tblusers` SET `_username`='$username',`_userbio`='$userbio',`_userage`='$userage',`_userlocation`='$location',`_userstate`='$country',`_userpin`='$userpin' WHERE `_id` = $id";
        } else {
            $encpassword = md5($userpassword);
            $sql = "UPDATE `tblusers` SET `_username`='$username',`_userbio`='$userbio',`_userage`='$userage',`_userlocation`='$location',`_userstate`='$country',`_userpin`='$userpin',`_userpassword`='$encpassword' WHERE `_id` = $id";
        }

        $query = mysqli_query($conn, $sql);
        if ($query) {
            $alert = new PHPAlert();
            $alert->success("Profile Updated");
            $_SESSION['userPassword'] = $encpassword;
        } else {
            $alert = new PHPAlert();
            $alert->warn("Something went wrong");
        }
    }
}

function _updatedb($newfile)
{
    require('_config.php');
    require('_alert.php');
    $id = $_SESSION['userId'];
    $sql = "UPDATE `tblusers` SET `_userdp`='$newfile' WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Profile Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _getsingleuser($id, $param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblusers` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

/* Setting Functions */

function _smsconfig($param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblsmsconfig`";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _savesmsconfig($suppliername, $apikey, $baseurl, $isactive)
{
    require('_config.php');
    require('_alert.php');
    $sql = "UPDATE `tblsmsconfig` SET `_suppliername`='$suppliername',`_apikey`='$apikey',`_baseurl`='$baseurl',`_supplierstatus`='$isactive' WHERE `_id` = 1";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Settings Saved");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _emailconfig($param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblemailconfig`";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _saveemailconfig($hostname, $hostport, $smtpauth, $emailid, $password, $sendername, $status)
{
    require('_config.php');
    require('_alert.php');
    $sql = "UPDATE `tblemailconfig` SET `_hostname`='$hostname',`_hostport`='$hostport',`_smtpauth`='$smtpauth',`_emailaddress`='$emailid',`_emailpassword`='$password',`_sendername`='$sendername',`_supplierstatus`='$status' WHERE `_id` = 1";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Settings Saved");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _siteconfig($param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblsiteconfig`";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _savesiteconfig($sitetitle, $siteemail, $timezone, $header, $footer, $css, $logo = '', $reslogo = '', $favicon = '')
{
    require('_config.php');
    require('_alert.php');
    if ($logo && $reslogo && $favicon) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone', `_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css', `_sitelogo`='$logo',`_sitereslogo`='$reslogo',`_favicon`='$favicon' WHERE `_id` = 1";
    }
    if ($logo && $reslogo) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone',`_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css', `_sitelogo`='$logo',`_sitereslogo`='$reslogo' WHERE `_id` = 1";
    }
    if ($reslogo && $favicon) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone',`_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css', `_sitereslogo`='$reslogo',`_favicon`='$favicon' WHERE `_id` = 1";
    }
    if ($logo && $favicon) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone',`_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css', `_sitelogo`='$logo',`_favicon`='$favicon' WHERE `_id` = 1";
    }
    if ($logo) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone', `_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css', `_sitelogo`='$logo' WHERE `_id` = 1";
    }
    if ($reslogo) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone', `_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css', `_sitereslogo`='$reslogo' WHERE `_id` = 1";
    }
    if ($favicon) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone', `_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css', `_favicon`='$favicon' WHERE `_id` = 1";
    }
    if (!$logo && !$reslogo && !$favicon) {
        $sql = "UPDATE `tblsiteconfig` SET `_sitetitle`='$sitetitle',`_siteemail`='$siteemail',`_timezone`='$timezone', `_customheader`='$header', `_customfooter`='$footer',  `_customcss`='$css'  WHERE `_id` = 1";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Settings Saved");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _paymentconfig($param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblpaymentconfig`";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _savepaymentconfig($suppliername, $apikey, $companyname, $isactive)
{
    require('_config.php');
    require('_alert.php');
    $sql = "UPDATE `tblpaymentconfig` SET `_suppliername`='$suppliername',`_apikey`='$apikey',`_companyname`='$companyname',`_supplierstatus`='$isactive' WHERE `_id` = 1";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Settings Saved");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

/* Ticket Functions */

function _saveticket($subject, $category, $status, $image, $user, $message)
{
    require('_config.php');
    if ($image) {
        $sql = "INSERT INTO `tbltickets`(`_title`, `_message`, `_image`, `_category`, `_subcategory`, `_useremail`, `_status`) VALUES ('$subject','$message','$image','$category','null','$user','$status')";
    } else {
        $sql = "INSERT INTO `tbltickets`(`_title`, `_message`, `_category`, `_subcategory`, `_useremail`, `_status`) VALUES ('$subject','$message','$category','null','$user','$status')";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['ticket_success'] = true;
        header("location:");
    } else {
        $_SESSION['ticket_error'] = true;
        header("location:");
    }
}

function _gettickets($ticketid = '', $status = '',$createdAt='', $limit = '', $startfrom = '')
{
    require('_config.php');
    $user = $_SESSION['userEmailId'];
    if ($status != '' && $ticketid == '') {

        if ($_SESSION['userType'] == 2) {
            $sql = "SELECT * FROM `tbltickets` WHERE `_status` = '$status'";
        } else {
            $sql = "SELECT * FROM `tbltickets` WHERE `_status` = '$status' AND `_useremail` = '$user'";
        }
    }
     else if ($ticketid != '' && $status == '') {
        if ($_SESSION['userType'] == 2) {
            $sql = "SELECT * FROM `tbltickets` WHERE `_id` = '$ticketid'";
        } else {
            $sql = "SELECT * FROM `tbltickets` WHERE `_id` = '$ticketid'  AND `_useremail` = '$user'";
        }
    } 
     else if ($createdAt != '' && $ticketid == '') {
        if ($_SESSION['userType'] == 2) {
            $sql = "SELECT * FROM `tbltickets` WHERE `Creation_at_Date` = '$createdAt'";
        } else {
            $sql = "SELECT * FROM `tbltickets` WHERE `Creation_at_Date` = '$createdAt'  AND `_useremail` = '$user'";
        }
    } 
    else {
        if ($_SESSION['userType'] == 2) {
            $sql = "SELECT * FROM `tbltickets` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
        } else {
            $sql = "SELECT * FROM `tbltickets` WHERE `_useremail` = '$user' ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
        }
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
                <?php if ($_SESSION['userType'] == 2) { ?>
                    <td><?php echo $data['_id']; ?></td>
                <?php } ?>
                <td><?php echo $data['_title']; ?></td>
                <?php if ($_SESSION['userType'] == 2) { ?>
                    <td><?php echo $data['_useremail']; ?></td>
                <?php } ?>
                <td><?php echo $data['_status']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td><a href="view-ticket?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-eye"></a>
                    <?php if ($_SESSION['userType'] == 2) { ?>
                        <a href='manage-tickets?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            <?php } ?>
            </tr>
            <hr>
        <?php }
    }
}

function _deleteticket($id)
{
    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tbltickets` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Ticket Deleted Permanently");
    }
}

function _updateticket($filter, $param, $id)
{
    require('_config.php');
    require('_alert.php');
    $sql = "UPDATE `tbltickets` SET `$filter`='$param' WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Ticket Updated");
    }
}

function _getsinglticket($id, $param)
{
    // $id = 2;
    require('_config.php');
    $sql = "SELECT * FROM `tbltickets` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _saveticketres($id, $message, $image = '', $email)
{
    require('_config.php');
    require('_alert.php');
    if ($image) {
        $sql = "INSERT INTO `tblticketres`(`_ticket_id`, `_message`, `_image`, `_useremail`) VALUES ('$id','$message','$image','$email')";
    } else {
        $sql = "INSERT INTO `tblticketres`(`_ticket_id`, `_message`, `_useremail`) VALUES ('$id','$message','$email')";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Responded Successfully");
    }
}

function _getticketres($id)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblticketres` WHERE `_ticket_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold"><i class="mdi mdi-share text-primary" style="font-size: 18px;"></i>&nbsp;&nbsp;<strong><?php echo $data['_useremail']; ?></strong></div>
                    <?php echo $data['_message']; ?>
                </div>
                <?php if ($data['_image']) { ?>
                    <a href="../uploads/tickets/<?php echo $data['_image'] ?>" class="badge bg-primary rounded-pill"><i style="font-size: 18px" class="mdi mdi-cloud-download text-light"></i></a>
                <?php } ?>
            </li>
        <?php }
    }
}



// Category Functions

function _createCategory($categoryname, $categoryDesc, $isactive)
{

    require('_config.php');
    require('_alert.php');

    if ($categoryname != '') {

        $subject = "Category Created";
        $message = "Category Created Successfully";
        $sql = "SELECT * FROM `tblcategory` WHERE `_categoryname` = '$categoryname'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $count = mysqli_num_rows($query);
            if ($count > 0) {
                $alert = new PHPAlert();
                $alert->warn("Category Already Exists");
            } else {

                $sql = "INSERT INTO `tblcategory`(`_categoryname`, `_categoryDescription`, `_status`) VALUES ('$categoryname','$categoryDesc','$isactive')";


                $query = mysqli_query($conn, $sql);
                if ($query) {

                    $alert = new PHPAlert();
                    $alert->success("Category Created");
                }
            }
        }
    } else {
        $alert = new PHPAlert();
        $alert->warn("All Feilds are Required");
    }
}

function _getCategory($_categoryname = '', $status = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    if ($status != '' && $_categoryname == '') {
        $sql = "SELECT * FROM `tblcategory` WHERE `_status` = '$status'";
    } else if ($_categoryname != '' && $status == '') {
        $sql = "SELECT * FROM `tblcategory` WHERE `_categoryname` LIKE '%$_categoryname%'";
    } else {
        $sql = "SELECT * FROM `tblcategory` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
                <td><?php echo $data['_categoryname']; ?></td>
                <td>

                    <label class="checkbox-inline form-switch">
                        <?php
            if ($data['_status'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
            if ($data['_status'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                        ?>
                    </label>
                </td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td><a href="edit-category?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                    <?php if ($_SESSION['userType'] == 2) { ?>
                        <a href='manage-category?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            <?php } ?>
            </tr>
        <?php }
    }
}

function _getSingleCategory($id, $param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblcategory` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _updateCategory($_categoryname, $categoryDesc, $isactive, $_id)
{

    require('_config.php');
    require('_alert.php');


    $sql = "UPDATE `tblcategory` SET `_categoryname`='$_categoryname' , `_categoryDescription`='$categoryDesc' , `_status`='$isactive' WHERE `_id` = $_id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Category Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _deleteCategory($id)
{

    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tblcategory` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Category Deleted Permanently");
    }
}

function _createSubCategory($subCategoryname, $categoryId, $subCategoryDesc, $isactive)
{

    require('_config.php');
    require('_alert.php');

    if ($subCategoryname != '') {

        $subject = "Sub Category Created";
        $message = "Sub Category Created Successfully";
        $sql = "SELECT * FROM `tblsubcategory` WHERE `_subcategoryname` = '$subCategoryname'";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $count = mysqli_num_rows($query);
            if ($count > 0) {
                $alert = new PHPAlert();
                $alert->warn("Sub Category Already Exists");
            } else {

                $sql = "INSERT INTO `tblsubcategory`( `_subcategoryname` , `_categoryid` , `_subcategorydesc`, `_status`) VALUES ('$subCategoryname','$categoryId','$subCategoryDesc','$isactive')";


                $query = mysqli_query($conn, $sql);
                if ($query) {

                    $alert = new PHPAlert();
                    $alert->success("Sub Category Created");
                }
            }
        }
    } else {
        $alert = new PHPAlert();
        $alert->warn("All Feilds are Required");
    }
}

function _getSubCategory($_subcategoryname = '', $limit = '', $startfrom = '')
{
    require('_config.php');

    if ($_subcategoryname != '') {
        $sql = "SELECT * FROM `tblsubcategory` WHERE `_subcategoryname` LIKE '%$_subcategoryname%'";
    } else {
        $sql = "SELECT * FROM `tblsubcategory` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                <td><?php echo $data['_subcategoryname']; ?></td>

                <td>

                    <label class="checkbox-inline form-switch">
                        <?php
            if ($data['_status'] == true) { ?><input disabled role="switch" name="isactive" value="true" checked type="checkbox"><?php }
            if ($data['_status'] != true) { ?><input disabled role="switch" name="isactive" value="true" type="checkbox"><?php }
                                                        ?>
                    </label>


                </td>
                <td><?php
            $catid = $data['_categoryid'];
            $sql = "SELECT * FROM `tblcategory` WHERE `_id` = $catid";
            $query = mysqli_query($conn, $sql);
            if ($query) {
                foreach ($query as $cat_data) {
                    echo $cat_data['_categoryname'];
                }
            }
                ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td><a href="edit-subcategory?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                    <?php if ($_SESSION['userType'] == 2) { ?>
                        <a href='manage-subcategory?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            <?php } ?>
            </tr>
        <?php }
    }
}

function _getSingleSubCategory($id, $param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblsubcategory` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _updateSubCategory($subCategoryname, $categoryId, $subCategoryDesc, $isactive, $_id)
{

    require('_config.php');
    require('_alert.php');


    $sql = "UPDATE `tblsubcategory` SET `_subcategoryname`='$subCategoryname' , `_categoryid`='$categoryId'  , `_subcategorydesc`='$subCategoryDesc'  , `_status`='$isactive' WHERE `_id` = $_id";


    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Sub Category Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _deleteSubCategory($id)
{

    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tblsubcategory` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Sub Category Deleted Permanently");
    }
}

function _showCategoryOptions($_categoryID = '')
{

    require('_config.php');


    if ($_categoryID != '') {

        $sql = "SELECT * FROM `tblcategory`  ";

        $query = mysqli_query($conn, $sql);
        if ($query) {
        ?>
            <label for="categoryId" class="form-label">Select Category</label>
            <select style="height: 40px;" id="categoryId" name="categoryId" onClick="getSubCategory(this.options[this.selectedIndex].value)" class="form-control form-control-lg"  required>

                <option selected disabled value="">Category</option>

                <?php
            foreach ($query as $data) {

                $currentId = $data['_id'];

                if ($_categoryID == $currentId) {
                ?>
                        <option value="<?php echo $data['_id']; ?>" selected> <?php echo $data['_categoryname']; ?> </option>
                    <?php
                } else {
                    ?>
                        <option value="<?php echo $data['_id']; ?>"> <?php echo $data['_categoryname']; ?> </option>
                <?php
                }
            }
                ?>

            </select>
            <div class="invalid-feedback">Please select proper category</div>
        <?php


        }
    } else {
        $sql = "SELECT * FROM `tblcategory`";
        $query = mysqli_query($conn, $sql);
        if ($query) { ?>
            <label for="categoryId" class="form-label">Select Category</label>
            <select style="height: 46px;" id="categoryId" name="categoryId" onClick="getSubCategory(this.options[this.selectedIndex].value)" class="form-control form-control-lg"  required>
                <option selected disabled value="">Select Category</option>
                <?php
            foreach ($query as $data) {
                ?>
                    <option value="<?php echo $data['_id']; ?>"> <?php echo $data['_categoryname']; ?> </option>
                <?php
            }
                ?>

            </select>
            <div class="invalid-feedback">Please select proper category</div>
        <?php
        }
    }
}

function _showSubCategoryOptions($_subcategoryID = '')
{

    require('_config.php');


    if ($_subcategoryID != '') {

        $sql = "SELECT * FROM `tblsubcategory` where `_id`=$_subcategoryID  ";

        $query = mysqli_query($conn, $sql);
        if ($query) {



        ?>
            <label for="subcategoryId" class="form-label">Select Sub-Category</label>
            <select style="height: 40px;" id="subcategoryId" name="subcategoryId" id="subcategory" class="form-control form-control-lg" required>

                <?php

            foreach ($query as $data) {
                ?>
                    <option value="<?php echo $data['_id']; ?>" selected> <?php echo $data['_subcategoryname']; ?> </option>
                <?php
            }

                ?>

            </select>
        <?php


        }
    } else {
        $sql = "SELECT * FROM `tblsubcategory`";

        $query = mysqli_query($conn, $sql);
        if ($query) {

        ?>
            <label for="subcategoryId" class="form-label">Select Sub-Category</label>
            <select style="height: 46px;" id="subcategoryId" name="subcategoryId" id="subcategory" class="form-control form-control-lg" required>


            </select>
        <?php


        }
    }
}


// All Blog Function 

function _createBlog($_blogtitle, $_blogdesc, $_blogcategory, $_blogsubcategory, $_blogmetadesc, $_blogimg, $_userid, $_status)
{
    require('_config.php');

    $sql = "INSERT INTO `tblblog`(`_blogtitle`, `_parmalink`, `_blogdesc`, `_blogcategory`, `_blogsubcategory`, `_blogmetadesc`,`_blogimg`, `_userid`, `_status`) VALUES ('$_blogtitle', '$_blogtitle','$_blogdesc', '$_blogcategory', '$_blogsubcategory', '$_blogmetadesc','$_blogimg', '$_userid', '$_status')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['blog_success'] = true;
        header("location:");
    } else {
        $_SESSION['blog_error'] = true;
        header("location:");
    }
}

function _getBlogs($blogtitle = '', $blogcategory = '', $blogsubcategory = '', $startfrom = '', $limit = '')
{
    require('_config.php');
    if ($blogtitle) {
        $sql = "SELECT * FROM `tblblog` WHERE `_blogtitle` LIKE '%$blogtitle%' ";
    }
    if ($blogcategory && !$blogsubcategory && !$blogtitle) {
        $sql = "SELECT * FROM `tblblog` WHERE `_blogcategory`='$blogcategory' ";
    }
    if ($blogsubcategory != '' && $blogcategory == '' && $blogtitle == '') {
        $sql = "SELECT * FROM `tblblog` WHERE `_blogsubcategory`='$blogsubcategory' ";
    }
    if ($blogcategory && $blogsubcategory) {
        $sql = "SELECT * FROM `tblblog` WHERE `_blogcategory`='$blogcategory' AND `_blogsubcategory` = '$blogsubcategory' ";
    }
    if (!$blogcategory && !$blogsubcategory && !$blogtitle) {
        $sql = "SELECT * FROM `tblblog` ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    }

    $query = mysqli_query($conn, $sql);
    if ($query) {

        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_blogtitle']; ?></td>
                <td>
                    <label class="checkbox-inline form-switch">
                        <?php
            if ($data['_status'] == 'true') {
                        ?>
                            <input disabled role="switch" name="isactive" value="true" checked type="checkbox">
                        <?php
            }
            if ($data['_status'] != 'true') {
                        ?>
                            <input disabled role="switch" name="isactive" value="false" type="checkbox">
                        <?php
            }
                        ?>
                    </label>
                </td>
                <td>
                    <?php
            $catid = $data['_blogcategory'];
            $sql = "SELECT * FROM `tblcategory` WHERE `_id` = $catid";
            $query = mysqli_query($conn, $sql);
            if ($query) {
                foreach ($query as $result) {
                    echo $result['_categoryname'];
                }
            }
                    ?>
                </td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <a href="edit-blog?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                    <?php if ($_SESSION['userType'] == 2) { ?>
                        <a href='manage-blog?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            <?php
            }
            ?>

            </tr>

        <?php

        }
    }
}

function updateBlog($_blogtitle, $_blogdesc, $_blogcategory, $_blogsubcategory, $_blogmetadesc, $_blogimg, $_status, $_id)
{

    require('_config.php');


    $sql = "UPDATE `tblblog` SET `_blogtitle`='$_blogtitle' , `_blogdesc`='$_blogdesc'  , `_blogcategory`='$_blogcategory'  , `_blogsubcategory`='$_blogsubcategory' , `_blogmetadesc`='$_blogmetadesc' , `_blogimg`='$_blogimg' , `_status`='$_status' WHERE `_id` = $_id";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['blog_success'] = true;
        header("location:");
    } else {
        $_SESSION['blog_error'] = true;
        header("location:");
    }
}


function _getSingleBlog($id, $param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblblog` WHERE `_id`=$id";
    $query = mysqli_query($conn, $sql);
    if ($query) {

        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _deleteBlog($id)
{

    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tblblog` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Blog Deleted Permanently");
    }
}


// currency Functions

function _createmarkup($base, $conversion, $price, $status)
{
    require('_config.php');
    require('_alert.php');
    $sql = "INSERT INTO `tblcurrency`(`_basecurrency`, `_conversioncurrency`, `_price`, `_status`) VALUES ('$base','$conversion','$price','$status')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Markup Created");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Markup Failed");
    }
}

function _getmarkup($conversion = '', $status = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    $user = $_SESSION['userEmailId'];
    if ($status != '' && $conversion == '') {
        $sql = "SELECT * FROM `tblcurrency` WHERE `_status` = '$status'";
    } else if ($conversion != '' && $status != '') {
        $sql = "SELECT * FROM `tblcurrency` WHERE `_conversioncurrency` = '$conversion'";
    } else {
        $sql = "SELECT * FROM `tblcurrency` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
                <td><?php echo $data['_basecurrency']; ?></td>
                <td><?php echo $data['_conversioncurrency']; ?></td>
                <td><?php echo $data['_price']; ?></td>
                <td>
                    <label class="checkbox-inline form-switch">
                        <?php
            if ($data['_status'] == 'true') {
                        ?>
                            <input disabled role="switch" name="isactive" value="true" checked type="checkbox">
                        <?php
            }
            if (!$data['_status']) {
                        ?>
                            <input disabled role="switch" name="isactive" value="false" type="checkbox">
                        <?php
            }
                        ?>
                    </label>
                </td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <a href='manage-currency?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php }
    }
}

function _getmarkupOnlyCurrency()
{

    require('_config.php');


    $sql = "SELECT * FROM `tblcurrency` ";

    $query = mysqli_query($conn, $sql);

    if ($query) {


        foreach ($query as $data) {
        ?>
            <option value="<?php echo $data['_conversioncurrency']; ?>"><?php echo $data['_conversioncurrency']; ?></option>
        <?php
        }
    }
}

function _conversion($amount, $currency)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblcurrency` WHERE `_conversioncurrency` = '$currency'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            $price = $data['_price'];
        }
        return $amount * $price;
    }
}

function _deletemarkup($id)
{

    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tblcurrency` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Markup Deleted Permanently");
    }
}


// Tax Functions

function _createtaxmarkup($name, $type, $currency, $amount, $status)
{
    require('_config.php');
    require('_alert.php');
    $sql = "INSERT INTO `tbltaxes`(`_taxname`, `_taxtype`, `_taxamount`,  `_taxcurrency`, `_status`) VALUES ('$name','$type','$amount', '$currency','$status')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Markup Created");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Markup Failed");
    }
}

function _gettaxmarkup($name = '', $status = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    $user = $_SESSION['userEmailId'];
    if ($status != '' && $name == '') {
        $sql = "SELECT * FROM `tbltaxes` WHERE `_status` = '$status'";
    } else if ($name != '' && $status != '') {
        $sql = "SELECT * FROM `tbltaxes` WHERE `_taxname` = '$name'";
    } else {
        $sql = "SELECT * FROM `tbltaxes` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
                <td><?php echo $data['_taxname']; ?></td>
                <td><?php echo $data['_taxtype']; ?></td>
                <td><?php echo $data['_taxamount']; ?></td>
                <td>
                    <label class="checkbox-inline form-switch">
                        <?php
            if ($data['_status'] == 'true') {
                        ?>
                            <input disabled role="switch" name="isactive" value="true" checked type="checkbox">
                        <?php
            }
            if (!$data['_status']) {
                        ?>
                            <input disabled role="switch" name="isactive" value="false" type="checkbox">
                        <?php
            }
                        ?>
                    </label>
                </td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <a href='manage-tax?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php }
    }
}

function _deletetax($id)
{
    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tbltaxes` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Tax Deleted Permanently");
    }
}

function _gettaxes()
{
    require('_config.php');
    $sql = "SELECT * FROM `tbltaxes` WHERE `_status` = 'true'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <h5 style="margin-top:10px"><?php echo $data['_taxname']; ?></h5>
            <?php if ($data['_taxtype'] == 'Variable') { ?>
                <input class="form-control" name="amount" type="text" readonly value="<?php echo $data['_taxamount']; ?>%">
            <?php } else {
            ?><input class="form-control" name="amount" type="text" readonly value="<?php echo $data['_taxcurrency']; ?>&nbsp;<?php echo $data['_taxamount']; ?>">
            <?php } ?>

        <?php }
    }
}

function _gettotal($sub, $currency, $discount)
{
    require('_config.php');
    $sql = "SELECT * FROM `tbltaxes` WHERE `_status` = 'true'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $tax = array();
        foreach ($query as $data) {
            if ($data['_taxtype'] == 'Variable') {
                $tax[] = ($data['_taxamount'] / 100) * $sub;
            } else {
                $tax[] = _conversion($data['_taxamount'], $currency);
            }
        }

        $final = $sub - $discount; // if not put it in else

        $arrtotal = $final + array_sum($tax);
        if ($arrtotal < 0) {
            $arrtotal = 0;
        }
        return $arrtotal;
    }
}


// Coupon Functions 

function _createcoupon($name, $type, $amount, $condition, $conamount, $validity, $currency, $couponprod)
{
    require('_config.php');
    require('_alert.php');
    $sql = "INSERT INTO `tblcoupon`(`_couponname`, `_coupontype`, `_couponamount`, `_couponcurrency`,`_couponcondition`, `_couponprod`, `_conamount`, `_maxusage`, `_totaluse`) VALUES ('$name','$type','$amount', '$currency','$condition', '$couponprod','$conamount','$validity',0)";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Coupon Created");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Coupon Failed");
    }
}

function _getcoupon($name = '', $type = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    if ($type != '' && $name == '') {
        $sql = "SELECT * FROM `tblcoupon` WHERE `_coupontype` = '$type'";
    } else if ($name != '' && $type != '') {
        $sql = "SELECT * FROM `tblcoupon` WHERE `_couponname` LIKE '%$name%'";
    } else {
        $sql = "SELECT * FROM `tblcoupon` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
                <td><?php echo $data['_couponname']; ?></td>
                <td><?php echo $data['_coupontype']; ?></td>
                <td><?php echo $data['_couponamount']; ?></td>
                <td><?php echo $data['_couponcondition']; ?></td>
                <td><?php echo $data['_conamount']; ?></td>
                <td><?php echo $data['_maxusage']; ?></td>
                <td><?php echo $data['_totaluse']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <a href='manage-coupon?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php }
    }
}

function _deletecoupon($id)
{
    require('_config.php');
    require('_alert.php');
    $sql = "DELETE FROM `tblcoupon` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Tax Deleted Permanently");
    }
}

function _validatecoupon($amount, $coupon, $currency, $prod)
{
    require('_config.php');
    require('_alert.php');
    $sql = "SELECT * FROM `tblcoupon` WHERE `_couponname` = '$coupon'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $count = mysqli_num_rows($query);
        if ($count >= 1) {
            foreach ($query as $data) {
                $vamount = $data['_conamount'];
                $vcondition = $data['_couponcondition'];
                $vlimit = $data['_maxusage'];
                $vusage = $data['_totaluse'];
                $vdiscount = $data['_couponamount'];
                $coupontype = $data['_coupontype'];
                $couponprod = $data['_couponprod'];
            }
            $vamount = _conversion($vamount, $currency);
            if($prod == $couponprod){
                if ($vusage < $vlimit) {
                    if ($vcondition == 'less') {
                        if ($amount < $vamount) {
                            if ($coupontype == 'Variable') {
                                $discount = ($vdiscount / 100) * $amount;
                                return $discount;
                            }
                            if ($coupontype == 'Fixed') {
                                $discount = _conversion($vdiscount, $currency);
                                return $discount;
                            }
                            if ($coupontype == 'Uncertain') {
                                $numbers = range(0, $vdiscount);
                                shuffle($numbers);
                                $famount = array_slice($numbers, 0, 1);
                                $discount = _conversion($famount[0], $currency);
                                return $discount;
                            }
                        } else {
                            return null;
                        }
                    }
                    if ($vcondition == 'more') {
                        if ($amount >= $vamount) {
                            if ($coupontype == 'Variable') {
                                $discount = ($vdiscount / 100) * $amount;
                                return $discount;
                            }
                            if ($coupontype == 'Fixed') {
                                $discount = _conversion($vdiscount, $currency);
                                return $discount;
                            }
                            if ($coupontype == 'Uncertain') {
                                $numbers = range(30, $vdiscount);
                                shuffle($numbers);
                                $famount = array_slice($numbers, 0, 1);
                                $discount = _conversion($famount[0], $currency);
                                return $discount;
                            }
                        } else {
                            return null;
                        }
                    }
                } else {
                    return null;
                }
            }else{
                return null;
            }
        } else {
            return null;
        }
    }
}

function _coupon($amount,$coupon,$currency)
{
    require('_config.php');
    $useremail = $_SESSION['userEmailId'];
    $sql = "INSERT INTO `tblcoupontrans`(`_couponname`, `_couponamount`, `_couponcurrency`, `_couponstatus`, `_useremail`) VALUES ('$coupon','$amount','$currency','pending','$useremail')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        return $conn->insert_id;
    }
}

function _updatecoupon($id, $status)
{
    require('_config.php');
    $sql = "UPDATE `tblcoupontrans` SET `_couponstatus`='$status' WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        if($status == 'success'){
            $sql = "SELECT * FROM `tblcoupontrans` WHERE `_id` = $id";
            $query = mysqli_query($conn,$sql);
            foreach($query as $data){
                $couponname = $data['_couponname'];
            }
            $sql = "UPDATE `tblcoupon` SET `_totaluse`= _totaluse + 1 WHERE `_couponname` = '$couponname'";
            echo $sql;
            $query = mysqli_query($conn,$sql);
        }        
    }
}

// Membership Module


function _createMembership($membershipname, $membershipdesc, $duration, $discount, $discounttype, $price, $isactive)
{
    require('_config.php');


    $sql = "INSERT INTO `tblmembership`(`_membershipname`, `_membershipdesc`, `_price`, `_benefit`, `_benefittype`, `_duration`, `_status`) VALUES ('$membershipname','$membershipdesc','$price','$discount','$discounttype','$duration','$isactive')";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['membership_success'] = true;
        header("location:");
    } else {
        $_SESSION['membership_error'] = true;
        header("location:");
    }
}


function _getMembership($membershipname = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    if ($membershipname != '') {
        $sql = "SELECT * FROM `tblmembership` WHERE `_membershipname` LIKE '%$membershipname%'";
    } else {
        $sql = "SELECT * FROM `tblmembership` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                <td><?php echo $data['_membershipname']; ?></td>
                <td>
                    <label class="checkbox-inline form-switch">
                        <?php
            if ($data['_status'] == 'true') {
                        ?>
                            <input disabled role="switch" name="isactive" value="true" checked type="checkbox">
                        <?php
            }
            if (!$data['_status']) {
                        ?>
                            <input disabled role="switch" name="isactive" value="false" type="checkbox">
                        <?php
            }
                        ?>
                    </label>
                </td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td><a href="edit-membership?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                    <?php if ($_SESSION['userType'] == 2) { ?>
                        <a href='manage-membership?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            <?php } ?>
            </tr>
        <?php }
    }
}


function _getSingleMembership($id, $param)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblmembership` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}


function _updateMembership($_id, $membershipname, $membershipdesc, $duration, $discount, $discounttype, $price, $isactive)
{

    require('_config.php');

    $sql = "UPDATE `tblmembership` SET `_membershipname`='$membershipname' , `_membershipdesc`='$membershipdesc' , `_duration`='$duration' , `_benefit`='$discount' , `_benefittype`='$discounttype' , `_price`='$price' , `_status`='$isactive' WHERE `_id` = $_id";


    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['membership_success'] = true;
        header("location:");
    } else {
        $_SESSION['membership_error'] = true;
        header("location:");
    }
}

function _deleteMembership($id)
{
    require('_config.php');
    require('_alert.php');

    $sql = "DELETE FROM `tblmembership` WHERE `_id` = '$id'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->error("Membership Deleted Permanently");
    }
}

function checkmembership($amount, $currency)
{
    require('_config.php');
    $useremail = $_SESSION['userEmailId'];
    $sql = "SELECT * FROM `tblusers` WHERE `_useremail` = '$useremail'";
    $query = mysqli_query($conn, $sql);
    foreach ($query as $data) {
        $membership = $data['_usermembership'];
    }
    if ($membership) {
        $sql = "SELECT * FROM `tblmembership` WHERE `_id` = $membership";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            foreach ($query as $data) {
                $type = $data['_benefittype'];
                $benifit = $data['_benefit'];
            }
            if ($type == 'Variable') {
                $discount = ($benifit / 100) * $amount;
                return $discount;
            } else {
                return _conversion($benifit, $currency);
            }
        }
    } else {
        return false;
    }
}

function _allmemberships()
{
    require('_config.php');
    $sql = "SELECT * FROM `tblmembership` WHERE `_status` = 'true'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <div class="col-lg-4">
                <div class="price-box">
                    <div class="">
                        <div class="price-label basic"><?php echo $data['_membershipname']; ?></div>
                        <div class="price">INR&nbsp;<?php echo $data['_price']; ?></div>
                        <div class="price-info">Per Month, For <?php echo $data['_duration']; ?> Month.</div>
                    </div>
                    <div class="info">
                        <ul>
                            <?php echo $data['_membershipdesc']; ?>
                        </ul>
                        <a href="payment?amount=<?php echo $data['_price']; ?>&currency=INR&prod=membership&id=<?php echo $data['_id']; ?>" style="margin-top:-20px" class="plan-btn">Join Plan</a>
                    </div>
                </div>
            </div>
        <?php }
    }
}

function _purchasememebership($userid, $memberid)
{
    require('_config.php');
    $duration = _getSingleMembership($memberid, '_duration');
    date_default_timezone_set('Asia/Kolkata');
    $date = strtotime(date('Y-m-d H:i:s'));
    $today = date('Y-m-d H:i:s');
    $enddata = date("Y-m-d", strtotime("+$duration month", $date));
    $sql = "UPDATE `tblusers` SET `_usermembership`='$memberid',`_usermemstart`='$today',`_usermemsleft`='$enddata' WHERE `_id` = $userid";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $sql = "SELECT * FROM `tblemailtemplates`";
        $query = mysqli_query($conn, $sql);
        foreach ($query as $data) {
            $template = $data['_purchasetemplate'];
        }
        $variables = array();
        $variables['name'] = _getsingleuser($userid, '_username');
        $variables['price'] = _getSingleMembership($memberid, '_price');
        $variables['product'] = _getSingleMembership($memberid, '_membershipname');
        $variables['date'] = date('M j, Y');
        $variables['companyname'] = _siteconfig('_sitetitle');
        $variables['paymentid'] = $_SESSION['transid'];
        $sendmail = _usetemplate($template, $variables);
        $message = 'Thank you for your purchase with ' . _siteconfig('_sitetitle') . '. We have mailed your order details on ' . _getsingleuser($userid, '_useremail') . '';
        _notifyuser(_getsingleuser($userid, '_useremail'), _getsingleuser($userid, '_userphone'), $sendmail, $message, 'Purchase Completed');
    }
}


function _usetemplate($template, $data)
{
    foreach ($data as $key => $value) {
        $template = str_replace('{{ ' . $key . ' }}', $value, $template);
    }

    return $template;
}

// Transcations


function _getTranscations($useremail = '', $amount = '', $status = '', $startfrom = '', $limit = '')
{

    require('_config.php');
    echo $amount;
    if ($useremail != '') {
        $sql = "SELECT * FROM `tblpayment` WHERE `_useremail` LIKE '%$useremail%' ";
    }
    if ($amount != '') {
        $sql = "SELECT * FROM `tblpayment` WHERE `_amount`='$amount' ";
    }
    if ($status != '' && $useremail == '' && $amount == '') {
        $sql = "SELECT * FROM `tblpayment` WHERE `_status`='$status' ";
    }
    if ($useremail == '' && $status == '' && $amount == '') {
        $sql = "SELECT * FROM `tblpayment` ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    }


    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr style="margin-bottom:-25px">
                <td><?php echo $data['_id']; ?></td>
                <td><?php echo $data['_useremail']; ?></td>
                <td><?php echo $data['_amount']; ?></td>
                <td><?php echo $data['_currency']; ?></td>
                <td>
                    <?php echo $data['_status']; ?>
                </td>
                <td><?php echo $data['_couponcode']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <a href="edit-transcation?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                </td>
            </tr>
        <?php
        } ?> <br> <?php
    }
}

function _getSingleTranscations($id, $param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tblpayment` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _updateTranscation($_id, $useremail, $amount, $couponcode, $currency, $isactive)
{
    require('_config.php');
    require('_alert.php');
    $sql = "UPDATE `tblpayment` SET `_useremail`='$useremail' , `_amount`='$amount' , `_currency`='$currency' , `_couponcode`='$couponcode' , `_status`='$isactive' WHERE `_id` = '$_id'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Transcation Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _getCouponTranscation($couponname = '', $couponamount = '', $startfrom = '', $limit = '')
{

    require('_config.php');


    if ($couponname != '' && $couponamount == '') {
        $sql = "SELECT * FROM `tblcoupontrans` WHERE `_couponname` LIKE '%$couponname%' ";
    }

    if ($couponamount != '' && $couponname == '') {
        $sql = "SELECT * FROM `tblcoupontrans` WHERE `_couponamount` LIKE '%$couponamount%' ";
    }

    if ($couponname == '' && $couponamount == '') {
        $sql = "SELECT * FROM `tblcoupontrans` ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    }

    $query = mysqli_query($conn, $sql);

    if ($query) {
        foreach ($query as $data) {
                  ?>
            <tr>
                <td><?php echo $data['_couponname']; ?></td>
                <td><?php echo $data['_couponamount']; ?></td>
                <td><?php echo $data['_couponcurrency']; ?></td>
                <td><?php echo $data['_useremail']; ?></td>
                <td><?php echo $data['_couponstatus']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
            </tr>
            <?php
        }
    }
}

function _getSingleCouponTranscations($id, $param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tblcoupontrans` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}

function _updateCouponTranscation($_id, $couponname, $couponamount, $useremail)
{

    require('_config.php');
    require('_alert.php');


    $sql = "UPDATE `tblcoupontrans` SET `_couponname`='$couponname' , `_couponamount`='$couponamount' , `_useremail`='$useremail' WHERE `_id` = '$_id'";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Coupon Transcation Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _payment($amount, $currency, $coupon = '', $prod, $prodid)
{
    if ($prod == 'membership') {
        $prodname = _getSingleMembership($prodid, '_membershipname');
    }
    if ($prod == 'invoice') {
        $prodname = _getSingleInvoice($prodid, '_refno');
    }
    require('_config.php');
    $useremail = $_SESSION['userEmailId'];
    $sql = "INSERT INTO `tblpayment`(`_useremail`, `_amount`, `_currency`, `_status`, `_producttitle`, `_productid`, `_producttype`, `_couponcode`) VALUES ('$useremail','$amount','$currency','pending','$prodname', '$prodid', '$prod', '$coupon')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        return $conn->insert_id;
    }
}

function _updatepayment($id, $status)
{
    require('_config.php');
    $sql = "UPDATE `tblpayment` SET `_status`='$status' WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        2 + 2;
    }
}


// Product Functions

function _getproduct($id, $type)
{
    require('_config.php');
    if ($type == 'membership') {
        $sql = "SELECT * FROM `tblmembership` WHERE `_id` = $id";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            foreach ($query as $data) { ?>
                <li style="border:none;" class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0"><?php echo $data['_membershipname']; ?></h6>
                        <small class="text-muted">Membership Purchase For <?php echo $data['_duration']; ?> Month.</small>
                    </div>
                    <span class="text-muted">INR&nbsp;<?php echo $data['_price']; ?></span>
                </li>
            <?php }
        }
    }
    if ($type == 'invoice') {
        $sql = "SELECT * FROM `tblinvoice` WHERE `_id` = $id";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            foreach ($query as $data) { ?>
                <li style="border:none;" class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Payment for Invoice : <?php echo $data['_refno']; ?>&nbsp;(Refrence Number)</h6>
                        <small class="text-muted">Invoice payment for requested service</small>
                    </div>
                    <!-- <span class="text-muted">INR&nbsp;<?php echo $data['_price']; ?></span> -->
                </li>
            <?php }
        }
    }
}


// Email Templates

function _updateEmailTemplate($templateName, $templateCode)
{

    require('_config.php');

    $emailtemp = $conn->real_escape_string($templateCode);
    $sql = "UPDATE `tblemailtemplates` SET `$templateName`='" . $emailtemp . "' WHERE `_id` = 2 ";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['template_success'] = true;
        header("location:");
    } else {
        $_SESSION['template_error'] = true;
        header("location:");
    }
}

function _getSingleEmailTemplate($templateName)
{
    require('_config.php');
    $sql = "SELECT * FROM `tblemailtemplates` WHERE `_id` = 2 ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$templateName];
        }
    }
}


// Invoice

function _createInvoice($_clientname, $_clientemail, $_clientnumber, $_clientaddress, $_invoicenote, $_refno, $_duedate, $_paymentstatus)
{

    require('_config.php');
    require('_alert.php');

    $sql = "INSERT INTO `tblinvoice`(`_clientname`,`_clientemail`,`_clientnumber`,`_clientaddress`,`_paymentstatus`,`_refno`,`_invoicenote`,`_duedate`) VALUES ('$_clientname','$_clientemail','$_clientnumber','$_clientaddress','$_paymentstatus','$_refno','$_invoicenote','$_duedate')";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Invoice Created");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Creation Failed");
    }
}



function _getInvoice($clientemail = '', $refno = '', $startfrom = '', $limit = '')
{

    require('_config.php');



    if ($clientemail != '' && $refno == '') {

        $sql = "SELECT * FROM `tblinvoice` where `_clientemail` LIKE '%$clientemail%' ";
    }

    if ($clientemail == '' && $refno != '') {

        $sql = "SELECT * FROM `tblinvoice` where `_refno` LIKE '%$refno%' ";
    }

    if ($clientemail == '' && $refno == '') {

        $sql = "SELECT * FROM `tblinvoice` ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    }




    $query = mysqli_query($conn, $sql);

    if ($query) {
        foreach ($query as $data) {
            ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                <td><?php echo $data['_clientname']; ?></td>
                <td><?php echo $data['_clientemail']; ?></td>

                <?php
            if ($data['_paymentstatus'] == 'UnPaid') {
                ?>
                    <td>
                        <span style="background-color:#dd4949; color:#fff; padding:3px 5px; border-radius:10px; ">
                            <?php echo $data['_paymentstatus']; ?>
                        </span>
                    </td>
                <?php
            } else {
                ?>
                    <td>
                        <span style="background-color:#86bd68; color:#fff; padding:3px 5px; border-radius:10px; ">
                            <?php echo $data['_paymentstatus']; ?>
                        </span>
                    </td>
                <?php
            }
                ?>


                <td><?php echo $data['_refno']; ?></td>
                <td><?php echo date("M j, Y", strtotime($data['_duedate'])); ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <a href="edit-invoice?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                    <a href='manage-invoice?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php
        }
    }
}


function _viewInvoice($startfrom = '', $limit = '')
{

    require('_config.php');
    $userid = $_SESSION['userId'];
    $useremail = _getsingleuser($userid, '_useremail');
    $sql = "SELECT * FROM `tblinvoice` WHERE `_clientemail` = '$useremail' ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                <?php
            if ($data['_paymentstatus'] == 'UnPaid') {
                ?>
                    <td>
                        <span style="background-color:#dd4949; color:#fff; padding:3px 5px; border-radius:10px; ">
                            <?php echo $data['_paymentstatus']; ?>
                        </span>
                    </td>
                <?php
            } else {
                ?>
                    <td>
                        <span style="background-color:#86bd68; color:#fff; padding:3px 5px; border-radius:10px; ">
                            <?php echo $data['_paymentstatus']; ?>
                        </span>
                    </td>
                <?php
            }
                ?>
                <td><?php echo date("M j, Y", strtotime($data['_duedate'])); ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <a href="view-invoice?invoiceno=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-eye"></a>
                </td>
            </tr>
        <?php
        }
    }
}



function _getSingleInvoice($id, $param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tblinvoice` WHERE `_id` = $id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}


function _updateInvoice($_id, $_clientname, $_clientemail, $_clientnumber, $_clientaddress, $_invoicenote, $_duedate, $_paymentstatus)
{

    require('_config.php');
    require('_alert.php');


    $sql = "UPDATE `tblinvoice` SET `_clientname`='$_clientname' , `_clientemail`='$_clientemail' , `_clientnumber`='$_clientnumber' ,`_clientaddress`='$_clientaddress',`_paymentstatus`='$_paymentstatus' , `_invoicenote`='$_invoicenote' , `_duedate`='$_duedate' WHERE `_id` = '$_id'";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Invoice Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}



function _deleteInvoice($id)
{
    require('_config.php');
    require('_alert.php');

    $sql = "DELETE FROM `tblinvoice` WHERE `_id` = '$id'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Invoice Deleted");
    }
}



function _addInvoiceItem($invoiceno, $productname, $invoicequantity, $invoiceamount)
{


    require('_config.php');
    require('_alert.php');

    $sql = "INSERT INTO `tblinvoiceitems`(`_invoiceno`,`_productname`,`_productquantity`,`_productamount`) VALUES ('$invoiceno','$productname','$invoicequantity','$invoiceamount')";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Invoice Item Added");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Creation Failed");
    }
}



function _getInvoiceItems($invoiceno, $startfrom = '', $limit = '')
{

    require('_config.php');


    $sql = "SELECT * FROM `tblinvoiceitems` where `_invoiceno`='$invoiceno' ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";


    $query = mysqli_query($conn, $sql);

    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                <td><?php echo $data['_productname']; ?></td>
                <td><?php echo $data['_productquantity']; ?></td>
                <td><?php echo $data['_productamount']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <span style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box" onclick="callEditItem(<?php echo $data['_invoiceno']; ?>,<?php echo $data['_id']; ?>)"></span>
                    <a href='edit-invoice?invoiceno=<?php echo $invoiceno ?>&itemno=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php
        }
    }
}



function _getSingleInvoiceItem($invoiceno, $id, $param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tblinvoiceitems` WHERE `_invoiceno` = '$invoiceno' AND `_id`='$id' ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}


function _updateInvoiceItems($_id, $invoiceno, $productname, $invoicequantity, $invoiceamount)
{

    require('_config.php');
    require('_alert.php');


    $sql = "UPDATE `tblinvoiceitems` SET `_productname`='$productname' , `_productquantity`='$invoicequantity' , `_productamount`='$invoiceamount'  WHERE `_id` = '$_id' AND `_invoiceno`='$invoiceno' ";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Invoice Item Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _deleteInvoiceItems($invoiceno, $id)
{
    require('_config.php');

    $sql = "DELETE FROM `tblinvoiceitems` WHERE `_invoiceno` = '$invoiceno' AND `_id`='$id' ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['forgot_success'] = true;
        header("location:edit-invoice?id=$invoiceno");
    }
}


// View Transcation
function _viewTranscation($useremail, $startfrom = '', $limit = '')
{
    require('_config.php');
    $sql = "SELECT * FROM `tblpayment` where `_useremail`='$useremail' AND `_status` != 'pending' ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                <td><?php echo $data['_producttitle']; ?></td>
                <td><?php echo $data['_amount']; ?></td>
                <td><?php echo $data['_producttype']; ?></td>
                <td>
                    <?php

            $couponcode = $data['_couponcode'];

            if ($couponcode) {
                echo $couponcode;
            } else {
                echo "No Coupon Code Applied";
            }
                    ?>
                </td>
                <td><?php echo $data['_status']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
            </tr>
        <?php
        }
    }
}

// Course //

function _createCourse($coursename, $courseDesc, $whatlearn, $requirements, $eligibitycriteria, $capacity, $enrollstatus, $thumbnail, $banner, $pricing, $status, $teacheremailid, $categoryid, $subcategoryid, $coursetype, $coursechannel, $courselevel, $evuluationlink, $startdate, $enddate, $discountprice)
{


    require('_config.php');

    $sql = "INSERT INTO `tblcourse`(`_coursename`,`_coursedescription`,`_whatlearn`,`_requirements`,`_eligibilitycriteria`,`_capacity`,`_enrollstatus`,`_thumbnail`,`_banner`,`_pricing`,`_status`,`_teacheremailid`,`_categoryid`,`_subcategoryid`,`_coursetype`,`_coursechannel`,`_courselevel`,`_evuluationlink`,`_startdate`,`_enddate`,`_discountprice`) VALUES ('$coursename','$courseDesc','$whatlearn','$requirements','$eligibitycriteria','$capacity','$enrollstatus','$thumbnail','$banner','$pricing','$status','$teacheremailid','$categoryid','$subcategoryid','$coursetype','$coursechannel','$courselevel','$evuluationlink','$startdate','$enddate','$discountprice')";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['course_success'] = true;
        header("location:");
    } else {
        $_SESSION['course_error'] = false;
        header("location:");
    }
}



function _getSingleCourse($id, $param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tblcourse` WHERE `_id`='$id' ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}


function _getCourse($coursename = '', $teacheremailid = '', $createdat = '', $startfrom = '', $limit = '')
{

    require('_config.php');


    if ($coursename && !$teacheremailid) {
        $sql = "SELECT * FROM `tblcourse` where `_coursename` LIKE '%$coursename%' ";
    }

    else if (!$coursename && $teacheremailid) {
        $sql = "SELECT * FROM `tblcourse` where `_teacheremailid` LIKE '%$teacheremailid%' ";
    }

    else if (!$teacheremailid && $createdat) {
        $sql = "SELECT * FROM `tblcourse` where `Creation_at_Date`='$createdat' ";
    }

    else if (!$coursename && !$teacheremailid) {
        $sql = "SELECT * FROM `tblcourse` ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    }




    $query = mysqli_query($conn, $sql);

    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                <td><?php echo $data['_coursename']; ?></td>
                <td>
                    <?php
            $teacherid = $data['_teacheremailid'];
            echo _getSingleUser($teacherid, '_useremail');
                    ?>
                </td>
                <td><?php echo $data['_coursetype']; ?></td>
                <td><?php echo $data['_status']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <a href="edit-course?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                    <a href='manage-course?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php
        }
    }
}

function _showCourses($_courseid = '')
{

    require('_config.php');


    if ($_courseid != '') {

        $sql = "SELECT * FROM `tblcourse`  ";

        $query = mysqli_query($conn, $sql);
        if ($query) {
        ?>
            <label for="courseid" class="form-label">Select Course</label>
            <select style="height: 40px;" id="courseid" name="courseid" class="form-control form-control-lg"  required>


                <?php
            foreach ($query as $data) {

                $currentId = $data['_id'];

                if ($_courseid == $currentId) {
                ?>
                        <option value="<?php echo $data['_id']; ?>" selected> <?php echo $data['_coursename']; ?> </option>
                    <?php
                } else {
                    ?>
                        <option value="<?php echo $data['_id']; ?>"> <?php echo $data['_coursename']; ?> </option>
                <?php
                }
            }
                ?>

            </select>
            <div class="invalid-feedback">Please select proper course</div>
        <?php


        }
    } else {
        $sql = "SELECT * FROM `tblcourse`";
        $query = mysqli_query($conn, $sql);
        if ($query) { ?>
            <label for="courseid" class="form-label">Select Course</label>
            <select style="height: 46px;" id="courseid" name="courseid" class="form-control form-control-lg" required>
                <option selected disabled value="">Course</option>
                <?php
            foreach ($query as $data) {
                ?>
                    <option value="<?php echo $data['_id']; ?>"> <?php echo $data['_coursename']; ?> </option>
                <?php
            }
                ?>

            </select>
            <div class="invalid-feedback">Please select proper course</div>
        <?php
        }
    }
}


function _updateCourse($_id, $coursename, $courseDesc, $whatlearn, $requirements, $eligibitycriteria, $capacity, $enrollstatus, $thumbnail, $banner, $pricing, $status, $teacheremailid, $categoryid, $subcategoryid, $coursetype, $coursechannel, $courselevel, $evuluationlink, $startdate, $enddate, $discountprice)
{

    require('_config.php');


    $sql = "UPDATE `tblcourse` SET `_coursename`='$coursename' ,`_coursedescription`='$courseDesc' , `_whatlearn`='$whatlearn',`_requirements`='$requirements' ,`_eligibilitycriteria`='$eligibitycriteria',`_capacity`='$capacity' , `_enrollstatus`='$enrollstatus',`_thumbnail`='$thumbnail' ,`_banner`='$banner' , `_pricing`='$pricing',`_status`='$status' ,`_teacheremailid`='$teacheremailid' , `_categoryid`='$categoryid',`_subcategoryid`='$subcategoryid' , `_coursetype`='$coursetype' , `_coursechannel`='$coursechannel' , `_courselevel`='$courselevel' , `_evuluationlink`='$evuluationlink' , `_startdate`='$startdate' , `_enddate`='$enddate' , `_discountprice`='$discountprice' WHERE `_id` = '$_id' ";


    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['course_success'] = true;
        header("location:");
    } else {
        $_SESSION['course_error'] = false;
        header("location:");
    }
}



function _deleteCourse($id)
{
    require('_config.php');
    require('_alert.php');

    $sql = "DELETE FROM `tblcourse` WHERE `_id`='$id' ";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Course Delete");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Deletion Failed");
    }
}




// Lessons //
function _createLesson($_courseid, $_lessonname, $_lessontype, $_lessonurl, $_recordedfilename, $_lessondescription, $_status, $_availablity)
{


    require('_config.php');

    $sql = "INSERT INTO `tbllessons`(`_courseid`,`_lessonname`,`_lessontype`,`_lessonurl`,`_recordedfilename`,`_lessondescription`,`_status`,`_availablity`) VALUES ('$_courseid','$_lessonname','$_lessontype','$_lessonurl','$_recordedfilename','$_lessondescription','$_status','$_availablity')";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['course_success'] = true;
        header("location:");
    } else {
        $_SESSION['course_error'] = false;
        header("location:");
    }
}


function _getSingleLesson($id, $param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tbllessons` WHERE `_id`='$id' ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}



function _getLessons($coursename = '', $lessonname = '', $createdAt = '', $startfrom = '', $limit = '')
{

    require('_config.php');


    if ($coursename && !$lessonname) {
        $sql = "SELECT * FROM `tbllessons` where `_courseid`='$coursename' ";
    }

    else if (!$createdAt && $lessonname) {
        $sql = "SELECT * FROM `tbllessons` where `_lessonname` LIKE '%$lessonname%' ";
    }

    else if (!$lessonname && $createdAt) {
        $sql = "SELECT * FROM `tbllessons` where `Creation_at_Date`='$createdAt' ";
    }

    if (!$coursename && !$lessonname && !$createdAt) {
        $sql = "SELECT * FROM `tbllessons` ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";
    }




    $query = mysqli_query($conn, $sql);

    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>

                <td>
                    <?php

            $courseid = $data['_courseid'];
            echo _getSingleCourse($courseid, '_coursename');

                    ?>
                </td>

                <td><?php echo $data['_lessonname']; ?></td>
                <td><?php echo $data['_status']; ?></td>
                <td><?php echo $data['_lessontype']; ?></td>
                <td><?php echo $data['_availablity']; ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <a href="edit-lesson?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                    <a href='manage-lesson?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php
        }
    }
}

function _updateLesson($_id, $_courseid, $_lessonname, $_lessontype, $_lessonurl, $_recordedfilename, $_lessondescription, $_status, $_availablity)
{

    require('_config.php');


    $sql = "UPDATE `tbllessons` SET `_courseid`='$_courseid' ,`_lessonname`='$_lessonname' ,`_lessondescription`='$_lessondescription' , `_status`='$_status',`_availablity`='$_availablity',`_lessontype`='$_lessontype',`_lessonurl`='$_lessonurl',`_recordedfilename`='$_recordedfilename'  WHERE `_id` = '$_id' ";


    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_SESSION['course_success'] = true;
        header("location:");
    } else {
        $_SESSION['course_error'] = false;
        header("location:");
    }
}



function _deleteLesson($id)
{
    require('_config.php');
    require('_alert.php');

    $sql = "DELETE FROM `tbllessons` WHERE `_id`='$id' ";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Lesson Delete");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Deletion Failed");
    }
}




// Slides //
function _createSlide($_courseid, $_slideurl, $_caption)
{


    require('_config.php');

    $sql = "INSERT INTO `tblslides` (`_courseid`,`_slideurl`,`_caption`) VALUES ('$_courseid','$_slideurl','$_caption')";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        $_SESSION['slide_success'] = true;
        header("location:");
    } else {
        $_SESSION['slide_error'] = false;
        header("location:");
    }
}


function _getSingleSlide($id, $courseid, $param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tblslides` WHERE `_id`='$id' ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}



function _getSlides($id, $startfrom = '', $limit = '')
{

    require('_config.php');


    $sql = "SELECT * FROM `tblslides` where `_courseid`='$id' ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                
                <td>
                    <?php

            $courseid = $data['_courseid'];
            echo _getSingleCourse($courseid, '_coursename');

                    ?>
                </td>
                
                <td> 
                    <a href="../uploads/banner/<?php echo $data['_slideurl']; ?>" target="_blank" class="mdi mdi-eye"></a>
                </td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <span style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box" onclick="callEditSlide(<?php echo $data['_courseid']; ?>,<?php echo $data['_id']; ?>)"></span>
                    
                    <a href='edit-course?id=<?php echo $data['_courseid']; ?>&slideid=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php
        }
    }
}


function _updateSlide($_id, $_courseid, $_slideurl, $_caption)
{

    require('_config.php');

    $sql = "UPDATE `tblslides` SET `_slideurl`='$_slideurl' ,`_caption`='$_caption'   WHERE `_id` = '$_id' AND `_courseid`='$_courseid' ";
    $query = mysqli_query($conn, $sql);


    if ($query) {
        $_SESSION['slide_update_success'] = true;
        header("location:");
    } else {
        $_SESSION['slide_update_error'] = false;
        header("location:");
    }
}



function _deleteSlide($id, $_courseid)
{
    require('_config.php');


    $sql = "DELETE FROM `tblslides` WHERE `_id`='$id' AND `_courseid`='$_courseid' ";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $_SESSION['course_success'] = true;
        header("location:edit-course?id=$_courseid");
    }
}


// Get Teachers

function _getTeachers($id = '')
{

    include("../includes/_config.php");


    if ($id != '') {

        $query = mysqli_query($conn, "SELECT * FROM tblusers WHERE _usertype='1' ");

        while ($row = mysqli_fetch_array($query)) {

            $rowId = $row['_id'];

            if ($id == $rowId) {
        ?>
                <option value="<?php echo htmlentities($row['_id']); ?>" selected ><?php echo htmlentities($row['_useremail']); ?></option>
                <?php
            } else {
                ?>
                <option value="<?php echo htmlentities($row['_id']); ?>"><?php echo htmlentities($row['_useremail']); ?></option>
                <?php
            }

        }

    } else {
        $query = mysqli_query($conn, "SELECT * FROM tblusers WHERE _usertype='1' ");

        while ($row = mysqli_fetch_array($query)) {
                ?>
                <option value="<?php echo htmlentities($row['_id']); ?>"><?php echo htmlentities($row['_useremail']); ?></option>
            <?php
        }
    }



}



// Slides //
function _createAttachment($_lessonid, $_attachementurl)
{

    require('_config.php');


    $sql = "INSERT INTO `tblattachements` (`_lessonid`,`_attachementurl`) VALUES ('$_lessonid','$_attachementurl')";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        $_SESSION['attachment_success'] = true;
        header("location:");
    } else {
        $_SESSION['attachment_error'] = true;
        header("location:");
    }
}


function _getSingleAttachment($id,$param)
{

    require('_config.php');
    $sql = "SELECT * FROM `tblattachements` WHERE `_id`='$id' ";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) {
            return $data[$param];
        }
    }
}


function _updateAttachment($_id, $_attachementurl)
{

    require('_config.php');


    $sql = "UPDATE `tblattachements` SET `_attachementurl`='$_attachementurl'  WHERE `_id` = '$_id'  ";
    $query = mysqli_query($conn, $sql);


  
    if ($query) {
        $_SESSION['attachment_edit_success'] = true;
        header("location:");
    } else {
        $_SESSION['attachment_edit_error'] = true;
        header("location:");
    }
}


function _deleteAttachment($id,$locationid)
{
    require('_config.php');


    $sql = "DELETE FROM `tblattachements` WHERE `_id`='$id' ";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        header("location:edit-lesson?id=$locationid");
    }
}



function _getAttachments($id, $startfrom = '', $limit = '')
{

    require('_config.php');


    $sql = "SELECT * FROM `tblattachements` where `_lessonid`='$id' ORDER BY `CreationDate` DESC LIMIT $startfrom , $limit ";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        foreach ($query as $data) {
        ?>
            <tr>
                <td><?php echo $data['_id']; ?></td>
                
                <td>
                    <?php

            $lessonid = $data['_lessonid'];
            echo _getSingleLesson($lessonid, '_lessonname');

                    ?>
                </td>

                <td><?php echo $data['_attachementurl']; ?></td>
                
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php
            if (strtotime($data['UpdationDate']) == '') {
                echo "Not Updated Yet";
            } else {
                echo date("M j, Y", strtotime($data['UpdationDate']));
            }
                    ?>
                </td>
                <td>
                    <span style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box" onclick="callEditAttachment(<?php echo $data['_lessonid']; ?>,<?php echo $data['_id']; ?>)"></span>
                    
                    <a href='edit-lesson?id=<?php echo $data['_lessonid']; ?>&attachmentid=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            </tr>
        <?php
        }
    }
}


?>