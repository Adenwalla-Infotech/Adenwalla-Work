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

$_id = $_GET['id'];


if (isset($_POST['submit'])) {
    $useremail = $_POST['useremail'];
    $amount = $_POST['transcationamount'];
    $couponcode = $_POST['couponcode'];
    $currency = $_POST['currency'];

    if (isset($_POST['isactive'])) {
        $isactive = $_POST['isactive'];
    } else {
        $isactive = false;
    }

    _updateTranscation( $_id , $useremail, $amount, $couponcode, $currency, $isactive);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Transaction No : <?php echo _getSingleTranscations($_id, '_id') ?> | <?php echo _siteconfig('_sitetitle'); ?></title>
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
                                <h4 class="card-title">Edit Transcations</h4>
                                <p class="card-description">
                                    When you edit user account, you must assign access credentials, a user type, and a security password to the user. User type define what actions the user has permission to perform. Security password secures users permission to access. You can create multiple user accounts that include administrative right
                                </p>

                                <form method="POST" action="" class="needs-validation" novalidate>
                                    <div class="row g-3">
                                        <div class="col">
                                            <label for="useremail" class="form-label">User Email</label>
                                            <input type="text" value="<?php echo _getSingleTranscations($_id, '_useremail'); ?>" class="form-control" placeholder="User Email" aria-label="user email" id="useremail" name="useremail" required>
                                            <div class="invalid-feedback">Please type correct useremail</div>
                                        </div>
                                        <div class="col">
                                            <label for="transcationamount" class="form-label">Transcations Amount</label>
                                            <input type="text" value="<?php echo _getSingleTranscations($_id, '_amount'); ?>" class="form-control" placeholder="Transcation Amount" aria-label="Transcation Amount" name="transcationamount" required>
                                            <div class="invalid-feedback">Please type correct amount</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">


                                        <div class="col">
                                            <label for="couponcode" class="form-label">Coupon Code</label>
                                            <input type="text" class="form-control" value="<?php echo _getSingleTranscations($_id, '_couponcode'); ?>" placeholder="Coupon Code" aria-label="coupon code" id="couponcode" name="couponcode" required>
                                            <div class="invalid-feedback">Please type correct coupon code</div>
                                            
                                        </div>

                                        <div class="col" style="margin-top: 40px;">
                                            <label class="checkbox-inline" style="margin-left: 20px;">
                                                <?php
                                                if (_getSingleTranscations($_id, '_status') == true) { ?><input name="isactive" value="true" checked type="checkbox">&nbsp;Is Active<?php }
                                                                                                                                                                                if (_getSingleTranscations($_id, '_status') != true) { ?><input name="isactive" value="true" type="checkbox">&nbsp;Is Active<?php }
                                                                                                                                                                                                                                                                                                            ?>
                                            </label>
                                            <div class="invalid-feedback">select correct status</div>
                                        </div>


                                    </div>



                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col">

                                            <label for="conversion" class="form-label">Conversion Currency</label>
                                            <select name="currency" style="height: 46px;" class="form-control form-control-lg" id="exampleFormControlSelect2">
                                                <option selected value="<?php echo _getSingleTranscations($_id, '_currency'); ?>" required ><?php echo _getSingleTranscations($_id, '_currency'); ?></option>
                                                <option value="USD">America (United States) Dollars – USD</option>
                                                <option value="AFN">Afghanistan Afghanis – AFN</option>
                                                <option value="ALL">Albania Leke – ALL</option>
                                                <option value="DZD">Algeria Dinars – DZD</option>
                                                <option value="ARS">Argentina Pesos – ARS</option>
                                                <option value="AUD">Australia Dollars – AUD</option>
                                                <option value="ATS">Austria Schillings – ATS</OPTION>

                                                <option value="BSD">Bahamas Dollars – BSD</option>
                                                <option value="BHD">Bahrain Dinars – BHD</option>
                                                <option value="BDT">Bangladesh Taka – BDT</option>
                                                <option value="BBD">Barbados Dollars – BBD</option>
                                                <option value="BEF">Belgium Francs – BEF</OPTION>
                                                <option value="BMD">Bermuda Dollars – BMD</option>

                                                <option value="BRL">Brazil Reais – BRL</option>
                                                <option value="BGN">Bulgaria Leva – BGN</option>
                                                <option value="CAD">Canada Dollars – CAD</option>
                                                <option value="XOF">CFA BCEAO Francs – XOF</option>
                                                <option value="XAF">CFA BEAC Francs – XAF</option>
                                                <option value="CLP">Chile Pesos – CLP</option>

                                                <option value="CNY">China Yuan Renminbi – CNY</option>
                                                <option value="CNY">RMB (China Yuan Renminbi) – CNY</option>
                                                <option value="COP">Colombia Pesos – COP</option>
                                                <option value="XPF">CFP Francs – XPF</option>
                                                <option value="CRC">Costa Rica Colones – CRC</option>
                                                <option value="HRK">Croatia Kuna – HRK</option>

                                                <option value="CYP">Cyprus Pounds – CYP</option>
                                                <option value="CZK">Czech Republic Koruny – CZK</option>
                                                <option value="DKK">Denmark Kroner – DKK</option>
                                                <option value="DEM">Deutsche (Germany) Marks – DEM</OPTION>
                                                <option value="DOP">Dominican Republic Pesos – DOP</option>
                                                <option value="NLG">Dutch (Netherlands) Guilders – NLG</OPTION>

                                                <option value="XCD">Eastern Caribbean Dollars – XCD</option>
                                                <option value="EGP">Egypt Pounds – EGP</option>
                                                <option value="EEK">Estonia Krooni – EEK</option>
                                                <option value="EUR">Euro – EUR</option>
                                                <option value="FJD">Fiji Dollars – FJD</option>
                                                <option value="FIM">Finland Markkaa – FIM</OPTION>

                                                <option value="FRF*">France Francs – FRF*</OPTION>
                                                <option value="DEM">Germany Deutsche Marks – DEM</OPTION>
                                                <option value="XAU">Gold Ounces – XAU</option>
                                                <option value="GRD">Greece Drachmae – GRD</OPTION>
                                                <option value="GTQ">Guatemalan Quetzal – GTQ</OPTION>
                                                <option value="NLG">Holland (Netherlands) Guilders – NLG</OPTION>
                                                <option value="HKD">Hong Kong Dollars – HKD</option>

                                                <option value="HUF">Hungary Forint – HUF</option>
                                                <option value="ISK">Iceland Kronur – ISK</option>
                                                <option value="XDR">IMF Special Drawing Right – XDR</option>
                                                <option value="INR">India Rupees – INR</option>
                                                <option value="IDR">Indonesia Rupiahs – IDR</option>
                                                <option value="IRR">Iran Rials – IRR</option>

                                                <option value="IQD">Iraq Dinars – IQD</option>
                                                <option value="IEP*">Ireland Pounds – IEP*</OPTION>
                                                <option value="ILS">Israel New Shekels – ILS</option>
                                                <option value="ITL*">Italy Lire – ITL*</OPTION>
                                                <option value="JMD">Jamaica Dollars – JMD</option>
                                                <option value="JPY">Japan Yen – JPY</option>

                                                <option value="JOD">Jordan Dinars – JOD</option>
                                                <option value="KES">Kenya Shillings – KES</option>
                                                <option value="KRW">Korea (South) Won – KRW</option>
                                                <option value="KWD">Kuwait Dinars – KWD</option>
                                                <option value="LBP">Lebanon Pounds – LBP</option>
                                                <option value="LUF">Luxembourg Francs – LUF</OPTION>

                                                <option value="MYR">Malaysia Ringgits – MYR</option>
                                                <option value="MTL">Malta Liri – MTL</option>
                                                <option value="MUR">Mauritius Rupees – MUR</option>
                                                <option value="MXN">Mexico Pesos – MXN</option>
                                                <option value="MAD">Morocco Dirhams – MAD</option>
                                                <option value="NLG">Netherlands Guilders – NLG</OPTION>

                                                <option value="NZD">New Zealand Dollars – NZD</option>
                                                <option value="NOK">Norway Kroner – NOK</option>
                                                <option value="OMR">Oman Rials – OMR</option>
                                                <option value="PKR">Pakistan Rupees – PKR</option>
                                                <option value="XPD">Palladium Ounces – XPD</option>
                                                <option value="PEN">Peru Nuevos Soles – PEN</option>

                                                <option value="PHP">Philippines Pesos – PHP</option>
                                                <option value="XPT">Platinum Ounces – XPT</option>
                                                <option value="PLN">Poland Zlotych – PLN</option>
                                                <option value="PTE">Portugal Escudos – PTE</OPTION>
                                                <option value="QAR">Qatar Riyals – QAR</option>
                                                <option value="RON">Romania New Lei – RON</option>

                                                <option value="ROL">Romania Lei – ROL</option>
                                                <option value="RUB">Russia Rubles – RUB</option>
                                                <option value="SAR">Saudi Arabia Riyals – SAR</option>
                                                <option value="XAG">Silver Ounces – XAG</option>
                                                <option value="SGD">Singapore Dollars – SGD</option>
                                                <option value="SKK">Slovakia Koruny – SKK</option>

                                                <option value="SIT">Slovenia Tolars – SIT</option>
                                                <option value="ZAR">South Africa Rand – ZAR</option>
                                                <option value="KRW">South Korea Won – KRW</option>
                                                <option value="ESP">Spain Pesetas – ESP</OPTION>
                                                <option value="XDR">Special Drawing Rights (IMF) – XDR</option>
                                                <option value="LKR">Sri Lanka Rupees – LKR</option>

                                                <option value="SDD">Sudan Dinars – SDD</option>
                                                <option value="SEK">Sweden Kronor – SEK</option>
                                                <option value="CHF">Switzerland Francs – CHF</option>
                                                <option value="TWD">Taiwan New Dollars – TWD</option>
                                                <option value="THB">Thailand Baht – THB</option>
                                                <option value="TTD">Trinidad and Tobago Dollars – TTD</option>

                                                <option value="TND">Tunisia Dinars – TND</option>
                                                <option value="TRY">Turkey New Lira – TRY</option>
                                                <option value="AED">United Arab Emirates Dirhams – AED</option>
                                                <option value="GBP">United Kingdom Pounds – GBP</option>
                                                <option value="USD">United States Dollars – USD</option>
                                                <option value="VEB">Venezuela Bolivares – VEB</option>

                                                <option value="VND">Vietnam Dong – VND</option>
                                                <option value="ZMK">Zambia Kwacha – ZMK</option>
                                            </select>
                                            <div class="invalid-feedback">select correct currency</div>
                                        </div>

                                    </div>

                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 250px;margin-left: -10px" class="btn btn-primary">Update Payment Transaction</button>
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

</html>