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

$_id = $_SESSION['userId'];
$membership = _getsingleuser($_id, '_usermembership');
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $useremail = $_POST['useremail'];
    $userphone = $_POST['userphone'];
    $userpassword = $_POST['userpassword'];
    $userage = $_POST['userage'];
    $userbio = $_POST['userbio'];
    $location = $_POST['location'];
    $pincode = $_POST['pincode'];
    $country = $_POST['country'];
    _updateProfile($username, $useremail, $userpassword, $userphone, $userage, $userbio, $location, $pincode, $country);
}

if (isset($_POST['update'])) {
    if ($_FILES["userdp"]["name"] != '') {
        $file = $_FILES["userdp"]["name"];
        $extension = substr($file, strlen($file) - 4, strlen($file));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $newfile = md5($file) . $extension;
            move_uploaded_file($_FILES["userdp"]["tmp_name"], "../uploads/profile/" . $newfile);
            _updatedb($newfile);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php echo _getsingleuser($_id, '_username'); ?> |
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

    <style>
        .img-account-profile {
            height: 10rem;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        .card {
            /* box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%); */
        }

        .card .card-header {
            font-weight: 500;
        }

        .card-header:first-child {
            border-radius: 1.35rem 1.35rem 0 0;
        }

        .card-header {
            padding: 1rem 1.35rem;
            margin-bottom: 0;
            background-color: rgba(33, 40, 50, 0.03);
            border-bottom: 1px solid rgba(33, 40, 50, 0.125);
        }

        .form-control,
        .dataTable-input {
            display: block;
            width: 100%;
            padding: 0.875rem 1.125rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1;
            color: #69707a;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #c5ccd6;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 0.35rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .stretch-card {
            padding: 0px;
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
    </style>
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
                        <div class="container-xl">
                            <div class="row">
                                <div class="col-xl-4">
                                    <!-- Profile picture card-->
                                    <div class="card mb-4 mb-xl-0">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="card-header">Profile Picture</div>
                                            <div class="card-body text-center">
                                                <!-- Profile picture image-->
                                                <?php
                                                    $userDp = _getsingleuser($_id, '_userdp');
                                                    if ($userDp) { ?>
                                                <img class="img-account-profile rounded-circle mb-2"
                                                    src="../uploads/profile/<?php echo _getsingleuser($_id, '_userdp'); ?>"
                                                    alt="" onclick="selectDP()">
                                                <?php } else { ?>
                                                <img class="img-account-profile rounded-circle mb-2"
                                                    src="http://bootdey.com/img/Content/avatar/avatar1.png" alt=""
                                                    onclick="selectDP()">
                                                <?php } ?>
                                                <!-- Profile picture help block-->
                                                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than
                                                    5 MB</div>
                                                <input style="display: none;" name="userdp" type="file" id="userdp">
                                                <!-- Profile picture upload button-->
                                                <button class="btn btn-primary" name="update" type="submit"><i
                                                        class="mdi mdi-content-save"></i>&nbsp;&nbsp;Update
                                                    Image</button>
                                            </div>
                                        </form>
                                    </div>
                                    <?php if (_getsingleuser($_id, '_usermemsleft')) { ?>
                                    <div class="card mb-4 mb-xl-0" style="margin-top: 25px;border-radius:22px">
                                        <div class="card-header">Purchased Membership <svg fill="green"
                                                xmlns="http://www.w3.org/2000/svg" style="width: 15px;float:right"
                                                viewBox="0 0 512 512">
                                                <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                <path
                                                    d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z" />
                                            </svg></div>
                                        <?php _getproduct($membership, 'membership'); ?>
                                        <hr style="margin-top: -0px;">
                                        <p class="p-3" style="margin-top: -20px;margin-left:3px">Expires at <a
                                                href="#">&nbsp;
                                                <?php echo date("M j, Y", strtotime(_getsingleuser($_id, '_usermemsleft'))); ?>
                                            </a><a href="#" style="color: red;float:right">Cancel Now</a></p>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="col-xl-8">
                                    <form action="" method="post" class="needs-validation" novalidate
                                        enctype="multipart/form-data">
                                        <!-- Account details card-->
                                        <div class="card mb-4">
                                            <div class="card-header">Account Details</div>
                                            <div class="card-body">
                                                <form>
                                                    <!-- Form Group (username)-->
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12">
                                                            <label class="small mb-1" for="inputEmailAddress">Username
                                                                (Name will appear on the site to other users)</label>
                                                            <input class="form-control" id="inputEmailAddress"
                                                                type="text" name="username"
                                                                placeholder="Enter your username"
                                                                value="<?php echo _getsingleuser($_id, '_username'); ?>">
                                                            <div class="invalid-feedback">Please type correct username
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 mb-3">
                                                        <!-- Form Group (Address)-->
                                                        <div class="col-md-6">
                                                            <label class="small mb-1"
                                                                for="inputLocation">Address</label>
                                                            <input class="form-control" id="inputLocation" type="text"
                                                                name="location" placeholder="Enter your location"
                                                                value="<?php echo _getsingleuser($_id, '_userlocation'); ?>">
                                                            <div class="invalid-feedback">Please type correct address
                                                            </div>
                                                        </div>
                                                        <!-- Form Group (Pincode)-->
                                                        <div class="col-md-6">
                                                            <label class="small mb-1"
                                                                for="inputLocation">Pincode</label>
                                                            <input class="form-control" id="inputLocation" type="text"
                                                                name="pincode" placeholder="Enter your pincode"
                                                                pattern="[0-9]{6}" maxlength="6"
                                                                value="<?php echo _getsingleuser($_id, '_userpin'); ?>">
                                                            <div class="invalid-feedback">Please type correct Pincode
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Form Row-->
                                                    <div class="row gx-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="small mb-1"
                                                                for="inputLocation">Country</label>
                                                            <select name="country" name="country" style="height: 45px;"
                                                                class="form-control">
                                                                <option
                                                                    value="<?php echo _getsingleuser($_id, '_userstate'); ?>">
                                                                    <?php echo _getsingleuser($_id, '_userstate'); ?>
                                                                </option>
                                                                <option disabled value="">Choose Country</option>
                                                                <option value="Afghanistan">Afghanistan</option>
                                                                <option value="Åland Islands">Åland Islands</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antarctica">Antarctica</option>
                                                                <option value="Antigua and Barbuda">Antigua and Barbuda
                                                                </option>
                                                                <option value="Argentina">Argentina</option>
                                                                <option value="Armenia">Armenia</option>
                                                                <option value="Aruba">Aruba</option>
                                                                <option value="Australia">Australia</option>
                                                                <option value="Austria">Austria</option>
                                                                <option value="Azerbaijan">Azerbaijan</option>
                                                                <option value="Bahamas">Bahamas</option>
                                                                <option value="Bahrain">Bahrain</option>
                                                                <option value="Bangladesh">Bangladesh</option>
                                                                <option value="Barbados">Barbados</option>
                                                                <option value="Belarus">Belarus</option>
                                                                <option value="Belgium">Belgium</option>
                                                                <option value="Belize">Belize</option>
                                                                <option value="Benin">Benin</option>
                                                                <option value="Bermuda">Bermuda</option>
                                                                <option value="Bhutan">Bhutan</option>
                                                                <option value="Bolivia">Bolivia</option>
                                                                <option value="Bosnia and Herzegovina">Bosnia and
                                                                    Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Bouvet Island">Bouvet Island</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Indian Ocean Territory">British
                                                                    Indian Ocean Territory</option>
                                                                <option value="Brunei Darussalam">Brunei Darussalam
                                                                </option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African
                                                                    Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island
                                                                </option>
                                                                <option value="Cocos (Keeling) Islands">Cocos (Keeling)
                                                                    Islands</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Congo">Congo</option>
                                                                <option value="Congo, The Democratic Republic of The">
                                                                    Congo, The Democratic Republic of The</option>
                                                                <option value="Cook Islands">Cook Islands</option>
                                                                <option value="Costa Rica">Costa Rica</option>
                                                                <option value="Cote D'ivoire">Cote D'ivoire</option>
                                                                <option value="Croatia">Croatia</option>
                                                                <option value="Cuba">Cuba</option>
                                                                <option value="Cyprus">Cyprus</option>
                                                                <option value="Czech Republic">Czech Republic</option>
                                                                <option value="Denmark">Denmark</option>
                                                                <option value="Djibouti">Djibouti</option>
                                                                <option value="Dominica">Dominica</option>
                                                                <option value="Dominican Republic">Dominican Republic
                                                                </option>
                                                                <option value="Ecuador">Ecuador</option>
                                                                <option value="Egypt">Egypt</option>
                                                                <option value="El Salvador">El Salvador</option>
                                                                <option value="Equatorial Guinea">Equatorial Guinea
                                                                </option>
                                                                <option value="Eritrea">Eritrea</option>
                                                                <option value="Estonia">Estonia</option>
                                                                <option value="Ethiopia">Ethiopia</option>
                                                                <option value="Falkland Islands (Malvinas)">Falkland
                                                                    Islands (Malvinas)</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia
                                                                </option>
                                                                <option value="French Southern Territories">French
                                                                    Southern Territories</option>
                                                                <option value="Gabon">Gabon</option>
                                                                <option value="Gambia">Gambia</option>
                                                                <option value="Georgia">Georgia</option>
                                                                <option value="Germany">Germany</option>
                                                                <option value="Ghana">Ghana</option>
                                                                <option value="Gibraltar">Gibraltar</option>
                                                                <option value="Greece">Greece</option>
                                                                <option value="Greenland">Greenland</option>
                                                                <option value="Grenada">Grenada</option>
                                                                <option value="Guadeloupe">Guadeloupe</option>
                                                                <option value="Guam">Guam</option>
                                                                <option value="Guatemala">Guatemala</option>
                                                                <option value="Guernsey">Guernsey</option>
                                                                <option value="Guinea">Guinea</option>
                                                                <option value="Guinea-bissau">Guinea-bissau</option>
                                                                <option value="Guyana">Guyana</option>
                                                                <option value="Haiti">Haiti</option>
                                                                <option value="Heard Island and Mcdonald Islands">Heard
                                                                    Island and Mcdonald Islands</option>
                                                                <option value="Holy See (Vatican City State)">Holy See
                                                                    (Vatican City State)</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="India">India</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="Iran, Islamic Republic of">Iran, Islamic
                                                                    Republic of</option>
                                                                <option value="Iraq">Iraq</option>
                                                                <option value="Ireland">Ireland</option>
                                                                <option value="Isle of Man">Isle of Man</option>
                                                                <option value="Israel">Israel</option>
                                                                <option value="Italy">Italy</option>
                                                                <option value="Jamaica">Jamaica</option>
                                                                <option value="Japan">Japan</option>
                                                                <option value="Jersey">Jersey</option>
                                                                <option value="Jordan">Jordan</option>
                                                                <option value="Kazakhstan">Kazakhstan</option>
                                                                <option value="Kenya">Kenya</option>
                                                                <option value="Kiribati">Kiribati</option>
                                                                <option value="Korea, Democratic People's Republic of">
                                                                    Korea, Democratic People's Republic of</option>
                                                                <option value="Korea, Republic of">Korea, Republic of
                                                                </option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Lao People's Democratic Republic">Lao
                                                                    People's Democratic Republic</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libyan Arab Jamahiriya">Libyan Arab
                                                                    Jamahiriya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macao">Macao</option>
                                                                <option
                                                                    value="Macedonia, The Former Yugoslav Republic of">
                                                                    Macedonia, The Former Yugoslav Republic of</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands
                                                                </option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Micronesia, Federated States of">
                                                                    Micronesia, Federated States of</option>
                                                                <option value="Moldova, Republic of">Moldova, Republic
                                                                    of</option>
                                                                <option value="Monaco">Monaco</option>
                                                                <option value="Mongolia">Mongolia</option>
                                                                <option value="Montenegro">Montenegro</option>
                                                                <option value="Montserrat">Montserrat</option>
                                                                <option value="Morocco">Morocco</option>
                                                                <option value="Mozambique">Mozambique</option>
                                                                <option value="Myanmar">Myanmar</option>
                                                                <option value="Namibia">Namibia</option>
                                                                <option value="Nauru">Nauru</option>
                                                                <option value="Nepal">Nepal</option>
                                                                <option value="Netherlands">Netherlands</option>
                                                                <option value="Netherlands Antilles">Netherlands
                                                                    Antilles</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="Northern Mariana Islands">Northern
                                                                    Mariana Islands</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau">Palau</option>
                                                                <option value="Palestinian Territory, Occupied">
                                                                    Palestinian Territory, Occupied</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea
                                                                </option>
                                                                <option value="Paraguay">Paraguay</option>
                                                                <option value="Peru">Peru</option>
                                                                <option value="Philippines">Philippines</option>
                                                                <option value="Pitcairn">Pitcairn</option>
                                                                <option value="Poland">Poland</option>
                                                                <option value="Portugal">Portugal</option>
                                                                <option value="Puerto Rico">Puerto Rico</option>
                                                                <option value="Qatar">Qatar</option>
                                                                <option value="Reunion">Reunion</option>
                                                                <option value="Romania">Romania</option>
                                                                <option value="Russian Federation">Russian Federation
                                                                </option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="Saint Helena">Saint Helena</option>
                                                                <option value="Saint Kitts and Nevis">Saint Kitts and
                                                                    Nevis</option>
                                                                <option value="Saint Lucia">Saint Lucia</option>
                                                                <option value="Saint Pierre and Miquelon">Saint Pierre
                                                                    and Miquelon</option>
                                                                <option value="Saint Vincent and The Grenadines">Saint
                                                                    Vincent and The Grenadines</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="Sao Tome and Principe">Sao Tome and
                                                                    Principe</option>
                                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                                <option value="Senegal">Senegal</option>
                                                                <option value="Serbia">Serbia</option>
                                                                <option value="Seychelles">Seychelles</option>
                                                                <option value="Sierra Leone">Sierra Leone</option>
                                                                <option value="Singapore">Singapore</option>
                                                                <option value="Slovakia">Slovakia</option>
                                                                <option value="Slovenia">Slovenia</option>
                                                                <option value="Solomon Islands">Solomon Islands</option>
                                                                <option value="Somalia">Somalia</option>
                                                                <option value="South Africa">South Africa</option>
                                                                <option
                                                                    value="South Georgia and The South Sandwich Islands">
                                                                    South Georgia and The South Sandwich Islands
                                                                </option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Svalbard and Jan Mayen">Svalbard and Jan
                                                                    Mayen</option>
                                                                <option value="Swaziland">Swaziland</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syrian Arab Republic">Syrian Arab
                                                                    Republic</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania, United Republic of">Tanzania,
                                                                    United Republic of</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Timor-leste">Timor-leste</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad and Tobago">Trinidad and Tobago
                                                                </option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks and Caicos Islands">Turks and
                                                                    Caicos Islands</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Emirates">United Arab
                                                                    Emirates</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="United States">United States</option>
                                                                <option value="United States Minor Outlying Islands">
                                                                    United States Minor Outlying Islands</option>
                                                                <option value="Uruguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Viet Nam">Viet Nam</option>
                                                                <option value="Virgin Islands, British">Virgin Islands,
                                                                    British</option>
                                                                <option value="Virgin Islands, U.S.">Virgin Islands,
                                                                    U.S.</option>
                                                                <option value="Wallis and Futuna">Wallis and Futuna
                                                                </option>
                                                                <option value="Western Sahara">Western Sahara</option>
                                                                <option value="Yemen">Yemen</option>
                                                                <option value="Zambia">Zambia</option>
                                                                <option value="Zimbabwe">Zimbabwe</option>
                                                            </select>
                                                            <div class="invalid-feedback">Please Select correct country
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="small mb-1"
                                                                for="inputEmailAddress">Password</label>
                                                            <input class="form-control" id="inputEmailAddress"
                                                                name="userpassword" type="password"
                                                                placeholder="Enter your password"
                                                                value="<?php echo _getsingleuser($_id, '_userpassword'); ?>">
                                                            <div class="invalid-feedback">Please type correct password
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12">
                                                            <label class="small mb-1" for="inputEmailAddress">Email
                                                                address</label>
                                                            <input class="form-control" id="inputEmailAddress"
                                                                type="email" placeholder="Enter your email address"
                                                                name="useremail"
                                                                value="<?php echo _getsingleuser($_id, '_useremail'); ?>">
                                                            <div class="invalid-feedback">Please type correct email
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 mb-3">
                                                        <!-- Form Group (phone number)-->
                                                        <div class="col-md-6">
                                                            <label class="small mb-1" for="inputPhone">Phone
                                                                number</label>
                                                            <input class="form-control" id="inputPhone" type="tel"
                                                                name="userphone" placeholder="Enter your phone number"
                                                                value="<?php echo _getsingleuser($_id, '_userphone'); ?>"
                                                                pattern="[1-9]{1}[0-9]{9}">
                                                            <div class="invalid-feedback">Please type correct Phone
                                                                Number</div>
                                                        </div>
                                                        <!-- Form Group (birthday)-->
                                                        <div class="col-md-6">
                                                            <label class="small mb-1"
                                                                for="inputBirthday">Birthday</label>
                                                            <input class="form-control" id="inputBirthday" type="date"
                                                                name="userage" placeholder="Enter your birthday"
                                                                value="<?php echo _getsingleuser($_id, '_userage'); ?>">
                                                            <div class="invalid-feedback">Please select proper birthdate
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 mb-3">
                                                        <div class="col-12">
                                                            <label class="small mb-1" for="inputBirthday">User Biography
                                                                (Bio will appear on the site to other users)</label>
                                                            <textarea name="userbio" class="form-control" cols="10"
                                                                rows="5" style="padding: 16px; line-height: 1.5;"
                                                                maxlength="500"><?php 
                                                                $userbio = _getsingleuser($_id, '_userbio'); 
                                                                if($userbio){
                                                                    echo $userbio;
                                                                }
                                                                else{
                                                                    echo "Max 500 Words";
                                                                }
                                                                ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Save changes button-->
                                                    <button name="submit" class="btn btn-primary"
                                                        style="margin-top: 10px;" type="submit"><i
                                                            class="mdi mdi-content-save"></i>&nbsp;&nbsp;Save
                                                        changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    <?php include('templates/_footer.php'); ?>
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <div class="container"></div>
        <script src="../includes/_validation.js"></script>

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

<script>
    function selectDP() {
        document.getElementById('userdp').click();
    }
</script>

</html>