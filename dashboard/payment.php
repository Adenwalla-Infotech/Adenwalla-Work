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

$_id =  $_SESSION['userId'];
$getprice = $_GET['amount'];
$currency = $_GET['currency'];
if(isset($_GET['prod'])&& isset($_GET['id'])){
    $product = $_GET['prod'];
    $productid = $_GET['id'];
}else{
    $product =null;
    $productid = null;
}
$getamount = _conversion($getprice,$currency);

$_SESSION['paybtn'] = '';
$_SESSION['transid'] = '';

$memebership = checkmembership($getamount,$currency);
if($memebership){
    $showcoupon = false;
    $applydiscount = $memebership;
    $couponcode = '';
}else{
    $showcoupon = true;
}


if(!isset($applydiscount)){
    $applydiscount = 0;
    $couponcode = '';
}


if (isset($_POST['pay'])) {
    if(isset($_POST['coupon'])){
        $couponcode = $_POST['coupon'];
        $_SESSION['couponid'] = _coupon($getamount,$couponcode,$currency);
        $applydiscount = _validatecoupon($getamount,$_POST['coupon'],$currency,$product);
    }else{
        $showcoupon = false;
        $couponcode = 'Membership';
        $applydiscount = $memebership;
    }
    $username = $_POST['username'];
    $useremail = $_POST['useremail'];
    $userphone = $_POST['userphone'];
    $location = $_POST['location'];
    $pincode = $_POST['pincode'];
    $country = $_POST['country'];
    $amount = _gettotal($getamount,$currency,$applydiscount);
    $_SESSION['paybtn'] = true;
    $_SESSION['transid'] = _payment($amount,$currency,$couponcode,$product,$productid);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Payment | <?php echo _siteconfig('_sitetitle'); ?></title>
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
        .form-control, .dataTable-input {
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
        .stretch-card{
            padding: 0px;
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
                    <form action="" method="POST">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="container-xl">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <!-- Account details card-->
                                        <div class="card mb-4">
                                            <div class="card-header">Payment Completion (Online Payment)</div>
                                            <div class="card-body">
                                                    <!-- Form Group (username)-->
                                                    <div class="row mb-3">
                                                        <div class="col-lg-4">
                                                            <label class="small mb-1" for="inputEmailAddress">Username (Name on the Invoice)</label>
                                                            <input class="form-control" id="inputEmailAddress" type="text" name="username" placeholder="Enter your username" value="<?php echo _getsingleuser($_id, '_username'); ?>">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="small mb-1" for="inputPhone">Phone number</label>
                                                            <input class="form-control" id="inputPhone" type="tel" name="userphone" placeholder="Enter your phone number" value="<?php echo _getsingleuser($_id, '_userphone'); ?>">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                                            <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter your email address" name="useremail" value="<?php echo _getsingleuser($_id, '_useremail'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 mb-3">
                                                        <!-- Form Group (Address)-->
                                                        <div class="col-md-4">
                                                            <label class="small mb-1" for="inputLocation">Address</label>
                                                            <input class="form-control" id="inputLocation" type="text" name="location" placeholder="Enter your location" value="<?php echo _getsingleuser($_id, '_userlocation'); ?>">
                                                        </div>
                                                        <!-- Form Group (Pincode)-->
                                                        <div class="col-md-4">
                                                            <label class="small mb-1" for="inputLocation">Pincode</label>
                                                            <input class="form-control" id="inputLocation" type="text" name="pincode" placeholder="Enter your pincode" value="<?php echo _getsingleuser($_id, '_userpin'); ?>">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="small mb-1" for="inputLocation">Country</label>
                                                            <select name="country" name="country" style="height: 45px;" class="form-control">
                                                                <option value="<?php echo _getsingleuser($_id, '_userstate'); ?>"><?php echo _getsingleuser($_id, '_userstate'); ?></option>
                                                                <option value="">Choose Country</option>
                                                                <option value="Afghanistan">Afghanistan</option>
                                                                <option value="Åland Islands">Åland Islands</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antarctica">Antarctica</option>
                                                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
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
                                                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Bouvet Island">Bouvet Island</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island</option>
                                                                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Congo">Congo</option>
                                                                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
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
                                                                <option value="Dominican Republic">Dominican Republic</option>
                                                                <option value="Ecuador">Ecuador</option>
                                                                <option value="Egypt">Egypt</option>
                                                                <option value="El Salvador">El Salvador</option>
                                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                                <option value="Eritrea">Eritrea</option>
                                                                <option value="Estonia">Estonia</option>
                                                                <option value="Ethiopia">Ethiopia</option>
                                                                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia</option>
                                                                <option value="French Southern Territories">French Southern Territories</option>
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
                                                                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                                                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="India">India</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
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
                                                                <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                                                <option value="Korea, Republic of">Korea, Republic of</option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macao">Macao</option>
                                                                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands</option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                                                <option value="Moldova, Republic of">Moldova, Republic of</option>
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
                                                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau">Palau</option>
                                                                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea</option>
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
                                                                <option value="Russian Federation">Russian Federation</option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="Saint Helena">Saint Helena</option>
                                                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                                <option value="Saint Lucia">Saint Lucia</option>
                                                                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
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
                                                                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                                <option value="Swaziland">Swaziland</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Timor-leste">Timor-leste</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Emirates">United Arab Emirates</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="United States">United States</option>
                                                                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                                <option value="Uruguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Viet Nam">Viet Nam</option>
                                                                <option value="Virgin Islands, British">Virgin Islands, British</option>
                                                                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                                                <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                                <option value="Western Sahara">Western Sahara</option>
                                                                <option value="Yemen">Yemen</option>
                                                                <option value="Zambia">Zambia</option>
                                                                <option value="Zimbabwe">Zimbabwe</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <?php if($product && $productid){ ?>
                                            <div class="card mb-4">
                                                <div class="card-header">Confirm Purchase (Products)</div>
                                                <?php _getproduct($productid,$product); ?>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <div class="col-lg-4">
                                    <div class="card mb-4">
                                        <div class="card-header">Amount Confirmation</div>
                                            <div class="card-body">
                                                <!-- Form Row-->
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h5>Sub Total</h5> <input class="form-control" name="amount" type="text" readonly value="<?php echo $currency;?>&nbsp;<?php echo round($getamount,2);?>">    
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <?php echo _gettaxes();?>
                                                        <hr style="margin-top: 30px;" class="solid">
                                                    </div>
                                                    <div class="col-lg-12" style="margin-top: 20px;">
                                                        <h5>Total Amount (To Pay)</h5> <input class="form-control" name="amount" type="text" readonly value="<?php echo $currency;?>&nbsp;<?php echo round(_gettotal($getamount,$currency,$applydiscount),2); ?>"> 
                                                        <?php if($memebership){ ?>
                                                            <p style="margin-top: 5px;color:green"><svg fill="green" xmlns="http://www.w3.org/2000/svg" style="width: 15px" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"/></svg>&nbsp;&nbsp;Membership Discount
                                                            <?php echo $currency; ?>&nbsp;<?php echo $memebership; ?></p>
                                                        <?php }?> 
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <?php if($couponcode && $applydiscount && !$memebership){ ?>
                                                            <h5 style="margin-top:10px">Coupon Code</h5>
                                                            <input type="text" value="<?php echo $couponcode; ?>" readonly name="coupon" placeholder="Coupon Code" class="form-control">
                                                            <p style="margin-top: 5px;color:green"><svg fill="green" xmlns="http://www.w3.org/2000/svg" style="width: 15px" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"/></svg>&nbsp;&nbsp;Redeemed Successfully<a href="" style="float:right">Reset Coupon</a></p>
                                                        <?php }if(!$applydiscount && $couponcode){ ?>
                                                            <h5 style="margin-top:10px">Coupon Code</h5>
                                                            <input type="text" name="coupon" value="<?php echo $couponcode; ?>" readonly placeholder="Coupon Code" class="form-control">
                                                            <p style="margin-top: 5px;color:red"><svg xmlns="http://www.w3.org/2000/svg" fill="red" style="width: 15px;" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"/></svg>&nbsp;&nbsp;Invalid Coupon<a href="" style="float:right">Reset Coupon</a></p>
                                                        <?php }?>
                                                        <?php if($showcoupon && !$couponcode){ ?>
                                                            <h5 style="margin-top:10px">Coupon Code</h5>
                                                            <input type="text" name="coupon" placeholder="Coupon Code" class="form-control">
                                                        <?php }?>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-left: 0px;">
                                                    <?php if(!$_SESSION['paybtn']){?>
                                                        <div class="col-12" style="padding: 0px;">
                                                        <button name="pay" class="btn btn-primary" style="margin-top: 30px;width:95%" type="submit"><svg xmlns="http://www.w3.org/2000/svg" style="width: 15px;" fill="white" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg>&nbsp;&nbsp;Continue</button>
                                                    </div>
                                                    <?php }?>
                                                    <?php if($_SESSION['paybtn']){?>
                                                        <?php if(round(_gettotal($getamount,$currency,$applydiscount),2) < 1){ ?>
                                                            <div class="col-12" style="padding: 0px">
                                                                <button name="payment" class="btn btn-success" style="margin-top: 30px;width:95%" type="button" id="rzp-button2">Pay&nbsp;&nbsp;<?php echo $currency;?>&nbsp;<?php echo round(_gettotal($getamount,$currency,$applydiscount),2);?></button>
                                                            </div>
                                                        <?php }else{ ?>
                                                            <div class="col-12" style="padding: 0px">
                                                                <button name="pay" id="rzp-button1" class="btn btn-success" style="margin-top: 30px;width:95%" type="button">Pay&nbsp;&nbsp;<?php echo $currency;?>&nbsp;<?php echo round(_gettotal($getamount,$currency,$applydiscount),2);?></button>
                                                            </div>
                                                        <?php }?>
                                                    <?php }?>
                                                </div>    
                                            </div>
                                        </div>
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
</body>
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<form action="success" method="post">
    <script>
        var options = {
            "key": "<?php echo _paymentconfig('_apikey'); ?>", // Enter the Key ID generated from the Dashboard
            "amount": "<?php echo ceil($amount * 100);?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            "currency": "<?php echo $currency; ?>",
            "name": "<?php echo _paymentconfig('_companyname'); ?>",
            "description": "Payment for your Purchase",
            "image": "http://localhost/Adenwalla-Infotech/moniqart-development/uploads/images/logo.png",
            // "order_id": "OD<?php echo rand(111111, 999999)?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            "handler": function (response){
                console.log('response',response);
                document.getElementById('transpay').click();
            },
            "prefill": {
                "name": "<?php echo $username; ?>",
                "email": "<?php echo $useremail; ?>",
                "contact": "<?php echo $userphone; ?>"
            },
            "notes": {
                "address": "Razorpay Corporate Office"
            },
            "theme": {
                "color": "#4B49AC"
            }
        };
        var rzp1 = new Razorpay(options);

        rzp1.on('payment.failed', function (response){
            window.location.href = "failed";
        });
        document.getElementById('rzp-button1').onclick = function(e){
            rzp1.open();  
            // e.preventDefault();
        }
    </script>
    <?php if($product && $productid){ ?>       
        <input type="text" name="product" value="<?php echo $product; ?>" style="display: none;">
        <input type="text" name="productid" value="<?php echo $productid; ?>" style="display: none;">
    <?php }?>
    <button id="transpay" type="submit" name="payment" hidden></button>
</form>
<form action="success" method="post">
    <script>
        document.getElementById('rzp-button2').onclick = function(e){
            document.getElementById('tranpay').click();
        }
    </script>
    <?php if($product && $productid){ ?>       
        <input type="text" name="product" value="<?php echo $product; ?>" style="display: none;">
        <input type="text" name="productid" value="<?php echo $productid; ?>" style="display: none;">
    <?php }?>
    <button id="tranpay" type="submit" name="payment" hidden></button>
</form>

</html>