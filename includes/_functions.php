<?php

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

function _signup($userpassword, $useremail, $username, $usertype, $userphone)
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
                $sql = "INSERT INTO `tblusers`(`_username`, `_useremail`, `_userphone`, `_usertype`, `_userstatus`, `_userpassword`, `_userotp`, `_userverify`) VALUES ('$username','$useremail', '$userphone','$usertype', 'true', '$enc_password', '$userotp', 'false')";

                $query = mysqli_query($conn, $sql);
                if ($query) {
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
            _notifyuser($useremail, $userphone, $message, $subject);
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
                echo "<script>";
                echo "window.location.href = 'login'";
                echo "</script>";
            } else {
                $alert = new PHPAlert();
                $alert->warn("Verification Failed");
            }
        } else {
            $alert = new PHPAlert();
            $alert->warn("Something Went Wrong");
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

        curl_setopt_array($curl, array(
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
        ));

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
                `_username` varchar(255) NOT NULL,
                `_useremail` varchar(255) NOT NULL,
                `_userphone` varchar(255) NOT NULL,
                `_usersite` varchar(255) NOT NULL,
                `_userlongitude` varchar(50) NULL,
                `_userlatitude` varchar(50) NULL,
                `_userbio` varchar(500) NULL,
                `_userage` varchar(10) NULL,
                `_userlocation` varchar(100) NULL,
                `_userstate` varchar(50) NULL,
                `_userpin` varchar(50) NULL,
                `_userdp` varchar(50) NULL,
                `_usertype` int(11) NOT NULL,
                `_userstatus` varchar(50) NOT NULL,
                `_userpassword` varchar(255) NOT NULL,
                `_userotp` int(100) NULL,
                `_userverify` varchar(50) NOT NULL,
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
                `_sitetitle` varchar(50) NOT NULL,
                `_siteemail` varchar(50) NOT NULL,
                `_timezone` varchar(50) NOT NULL,
                `_customheader` text NOT NULL,
                `_customfooter` text NOT NULL,
                `_customcss` text NOT NULL,
                `_sitelogo` varchar(100) NOT NULL,
                `_sitereslogo` varchar(100) NOT NULL,
                `_favicon` varchar(100) NOT NULL,
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
                `_status` enum('open','closed','resolved') NOT NULL DEFAULT 'open',
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";

            $ticket_comment = "CREATE TABLE IF NOT EXISTS `tblticketres` (
                `_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                `_ticket_id` int(11) NOT NULL,
                `_message` text NOT NULL,
                `_image` varchar(255) NOT NULL,
                `_useremail` varchar(255) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";

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
                `_blogtitle` varchar(50) NOT NULL,
                `_parmalink` varchar(50) NOT NULL,
                `_blogdesc` varchar(500) NOT NULL,
                `_blogcategory` varchar(50) NOT NULL,
                `_blogsubcategory` varchar(50) NOT NULL,
                `_blogmetadesc` varchar(150) NOT NULL,
                `_blogimg` varchar(50) NOT NULL,
                `_userid` varchar(50) NOT NULL,
                `_status` varchar(20) NOT NULL,
                `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `UpdationDate` datetime NULL ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";



            $tables = [$admin_table, $sms_config, $email_config, $site_config, $tickets_table, $ticket_comment, $contact_table, $category_table, $subcategory_table, $blog_table];

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

                $data = [$admin_data, $sms_data, $email_data, $site_data];

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

function _createuser($username, $useremail, $usertype, $userphone, $userwebsite,  $isactive, $isverified, $notify)
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
                $sql = "INSERT INTO `tblusers`(`_username`, `_useremail`, `_userphone`, `_usertype`, `_userstatus`,`_usersite`, `_userotp`, `_userverify`) VALUES ('$username','$useremail', '$userphone', '$usertype', '$isactive','$userwebsite', '$userotp', '$isverified')";

                $query = mysqli_query($conn, $sql);
                if ($query) {
                    if ($notify) {
                        _notifyuser($useremail, $userphone, $message, $subject);
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

function _notifyuser($useremail = '', $userphone = '', $message, $subject = '')
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

            curl_setopt_array($curl, array(
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
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $alert = new PHPAlert();
                $alert->warn("SMS not sent");
            } else {
                $_SESSION['forgot_success'] = true;
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
            }
            //To address and namS
            $mail->addAddress($useremail); //Recipient name is optional

            //Address to which recipient will reply
            $mail->addReplyTo($data['_emailaddress'], "Reply");

            $mail->isHTML(true);

            $mail->Subject = $subject;
            $mail->Body = "<i>$message</i>";
            if ($mail->send()) {
                $_SESSION['send_mail'] = true;
            }
        }
    }
}

function _getuser($username = '', $usertype = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    if ($usertype != '' && $username == '') {
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
                        <?php echo date("F j, Y", strtotime($data['UpdationDate'])); ?>
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
                        <?php echo date("F j, Y", strtotime($data['UpdationDate'])); ?>
                    </td>
                    <td><a href="edit-user?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-pencil-box"></a>
                        <a href='manage-users?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                    </td>
                </tr>
            <?php }
        }
    } else {
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
                        <?php echo date("F j, Y", strtotime($data['UpdationDate'])); ?>
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
    $email =  $_SESSION['userEmailId'];
    $phone = $_SESSION['userPhoneNo'];
    $id =  $_SESSION['userId'];
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
    $id =  $_SESSION['userId'];
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

/* Ticket Functions */

function _saveticket($subject, $category, $status, $image, $user, $message)
{
    require('_config.php');
    require('_alert.php');
    if ($image) {
        $sql = "INSERT INTO `tbltickets`(`_title`, `_message`, `_image`, `_category`, `_subcategory`, `_useremail`, `_status`) VALUES ('$subject','$message','$image','$category','null','$user','$status')";
    } else {
        $sql = "INSERT INTO `tbltickets`(`_title`, `_message`, `_category`, `_subcategory`, `_useremail`, `_status`) VALUES ('$subject','$message','$category','null','$user','$status')";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Ticket Generated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
    }
}

function _gettickets($ticketid = '', $status = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    $user =  $_SESSION['userEmailId'];
    if ($status != '' && $ticketid == '') {
        if ($_SESSION['userType'] == 2) {
            $sql = "SELECT * FROM `tbltickets` WHERE `_status` = '$status'";
        } else {
            $sql = "SELECT * FROM `tbltickets` WHERE `_status` = '$status' AND `_useremail` = '$user'";
        }
    } else if ($ticketid != '' && $status != '') {
        if ($_SESSION['userType'] == 2) {
            $sql = "SELECT * FROM `tbltickets` WHERE `_id` = '$ticketid'";
        } else {
            $sql = "SELECT * FROM `tbltickets` WHERE `_id` = '$ticketid'  AND `_useremail` = '$user'";
        }
    } else {
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
                    <?php echo date("M j, Y", strtotime($data['UpdationDate'])); ?>
                </td>
                <td><a href="view-ticket?id=<?php echo $data['_id']; ?>" style="font-size: 20px;cursor:pointer;color:green" class="mdi mdi-eye"></a>
                    <?php if ($_SESSION['userType'] == 2) { ?>
                        <a href='manage-tickets?id=<?php echo $data['_id']; ?>&del=true' class="mdi mdi-delete-forever" style="font-size: 20px;cursor:pointer; color:red"><a>
                </td>
            <?php } ?>
            </tr>
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


// 
// Category Functions
// 

/// ADD CATEGORY
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


/// Get CATEGORY

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
                    <?php echo date("M j, Y", strtotime($data['UpdationDate'])); ?>
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


// Get Single Category
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

// Update Category
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

// Delete Category
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

// 
// Sub Category Functions
// 



/// ADD Sub CATEGORY
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


/// Get CATEGORY

function _getSubCategory($_subcategoryname = '', $categoryId = '', $limit = '', $startfrom = '')
{
    require('_config.php');
    if ($categoryId != '' && $_subcategoryname == '') {
        $sql = "SELECT * FROM `tblsubcategory` WHERE `_categoryid` LIKE '%$categoryId%'";
    } else if ($_subcategoryname != '' && $categoryId == '') {
        $sql = "SELECT * FROM `tblsubcategory` WHERE `_subcategoryname` = '$_subcategoryname'";
    } else {
        $sql = "SELECT * FROM `tblsubcategory` ORDER BY `CreationDate` DESC LIMIT $startfrom, $limit";
    }
    $query = mysqli_query($conn, $sql);
    if ($query) {
        foreach ($query as $data) { ?>
            <tr>
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
                        foreach ($query as $data) {
                            echo $data['_categoryname'];
                        }
                    }
                    ?></td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['CreationDate'])); ?>
                </td>
                <td>
                    <?php echo date("M j, Y", strtotime($data['UpdationDate'])); ?>
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


// Get Single Category
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

// Update Category
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

// Delete Category
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
            <select style="height: 46px;" id="categoryId" name="categoryId" class="form-control form-control-lg" id="exampleFormControlSelect2" required>

                <option>Category</option>

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
        <?php


        }
    } else {
        $sql = "SELECT * FROM `tblcategory`";

        $query = mysqli_query($conn, $sql);
        if ($query) {

        ?>
            <label for="categoryId" class="form-label">Select Category</label>
            <select style="height: 46px;" id="categoryId" name="categoryId" class="form-control form-control-lg" id="exampleFormControlSelect2" required>
                <option>Category</option>
                <?php
                foreach ($query as $data) {
                ?>
                    <option value="<?php echo $data['_id']; ?>"> <?php echo $data['_categoryname']; ?> </option>
                <?php
                }
                ?>

            </select>
        <?php


        }
    }
}

function _showSubCategoryOptions($_subcategoryID = '')
{

    require('_config.php');


    if ($_subcategoryID != '') {

        $sql = "SELECT * FROM `tblsubcategory`  ";

        $query = mysqli_query($conn, $sql);
        if ($query) {
        ?>
            <label for="subcategoryId" class="form-label">Select Sub-Category</label>
            <select style="height: 46px;" id="subcategoryId" name="subcategoryId" class="form-control form-control-lg" id="exampleFormControlSelect2" required>

                <option> Sub Category</option>


                <?php

                foreach ($query as $data) {

                    $currentId = $data['_id'];

                    if ($_subcategoryID == $currentId) {
                        ?>
                            <option  value="<?php echo $data['_id']; ?>" selected> <?php echo $data['_subcategoryname']; ?> </option>
                        <?php

                    } 
                    else {
                            ?>
                                <option value="<?php echo $data['_id']; ?>"> <?php echo $data['_subcategoryname']; ?> </option>
                            <?php
                    }
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
            <select style="height: 46px;" id="subcategoryId" name="subcategoryId" class="form-control form-control-lg" id="exampleFormControlSelect2" required>
                <option> Sub Category</option>
                <?php
                    foreach ($query as $data) {
                        ?>
                            <option  value="<?php echo $data['_id']; ?>"> <?php echo $data['_subcategoryname']; ?> </option>
                        <?php
                    }
                ?>

            </select>
        <?php


        }
    }
}


function _createBlog($_blogtitle, $_blogdesc, $_blogcategory, $_blogsubcategory, $_blogmetadesc, $_blogimg, $_userid, $_status)
{
    require('_config.php');
    require('_alert.php');

    $sql = "INSERT INTO `tblblog`(`_blogtitle`, `_blogdesc`, `_blogcategory`, `_blogsubcategory`, `_blogmetadesc`,`_blogimg`, `_userid`, `_status`) VALUES ('$_blogtitle','$_blogdesc', '$_blogcategory', '$_blogsubcategory', '$_blogmetadesc','$_blogimg', '$_userid', '$_status')";

    $query = mysqli_query($conn, $sql);
    if ($query) {


        $alert = new PHPAlert();
        $alert->success("Blog Created");
    }
}

function _getBlogs($blogtitle = '', $blogcategory = '', $blogsubcategory = '', $startfrom = '', $limit = '')
{

    require('_config.php');

    if ($blogtitle != '' && $blogcategory == '' && $blogsubcategory == '') {
        $sql = "SELECT * FROM `tblblog` WHERE `_blogtitle` LIKE '%$blogtitle%' ";
    } 
    else if ($blogcategory != '' && $blogsubcategory == '' && $blogtitle == '' ) {
        $sql = "SELECT * FROM `tblblog` WHERE `_blogcategory`='$blogcategory' ";
    } 
    else if ($blogsubcategory != '' && $blogcategory == '' && $blogtitle == '' ) {
        // echo "hi";
        $sql = "SELECT * FROM `tblblog` WHERE `_blogsubcategory`=3 ";
    } 
    else if ($blogcategory != '' && $blogsubcategory != '' && $blogtitle == '') {
        $sql = "SELECT * FROM `tblblog` WHERE `_blogcategory`='$blogcategory' AND `_blogsubcategory` = '$blogsubcategory' ";
    } 
    else {
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
                        if ($data['_status'] == 'true')
                        { 
                            ?>
                            <input disabled role="switch" name="isactive" value="true" checked type="checkbox">
                            <?php
                        }
                        if ($data['_status'] == 'false') 
                        { 
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
                    <?php echo date("M j, Y", strtotime($data['UpdationDate'])); ?>
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

function updateBlog($_blogtitle, $_blogdesc, $_blogcategory, $_blogsubcategory, $_blogmetadesc, $_blogimg , $_status , $_id){

    require('_config.php');
    require('_alert.php');


    $sql = "UPDATE `tblblog` SET `_blogtitle`='$_blogtitle' , `_blogdesc`='$_blogdesc'  , `_blogcategory`='$_blogcategory'  , `_blogsubcategory`='$_blogsubcategory' , `_blogmetadesc`='$_blogmetadesc' , `_blogimg`='$_blogimg' , `_status`='$_status' WHERE `_id` = $_id";


    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = new PHPAlert();
        $alert->success("Blog Updated");
    } else {
        $alert = new PHPAlert();
        $alert->warn("Something went wrong");
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




?>