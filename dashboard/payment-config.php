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
    $suppliername = $_POST['suppliername'];
    $apikey = $_POST['apikey'];
    $companyname = $_POST['companyname'];
    if(isset($_POST['isactive'] ) ){
        $isactive = $_POST['isactive'];
    }else{
        $isactive = false;
    }
    _savepaymentconfig($suppliername,$apikey,$companyname,$isactive);
}
if(isset($_POST['send'])){
    $phonenumber = $_POST['phone'];
    $message = $_POST['message'];

    _notifyuser('',$phonenumber,$message,'');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Payment Config | <?php echo _siteconfig('_sitetitle'); ?></title>
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
                  <h4 class="card-title">Payment Configuration (Razorpay)</h4>
                  <p class="card-description">
                    Log in to your Razorpay account. Navigate to Settings > All settings > API Keys. If you have previously created an API key, paste the credentials over here, Kindly fo not make any changes over here if you have no knowlddge of API contact support in case of any confusion.
                  </p>
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Supplier name" aria-label="user name" value="<?php echo _paymentconfig('_suppliername'); ?>" name="suppliername" required>
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" placeholder="API Key" aria-label="api key" value="<?php echo _paymentconfig('_apikey'); ?>" name="apikey" required>
                            </div>
                        </div>
                        <div class="row g-3" style="margin-top: 20px;">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Company Name" aria-label="api key" value="<?php echo _paymentconfig('_companyname'); ?>" name="companyname" required>
                            </div>
                            <div class="col" style="margin-top: 10px;">
                                <label class="checkbox-inline" style="margin-left: 20px;">
                                    <?php 
                                        if( _paymentconfig('_supplierstatus')==true){?><input name="isactive" value="true" checked type="checkbox">&nbsp;Is Active<?php }
                                        if( _paymentconfig('_supplierstatus')!=true){?><input name="isactive" value="true" type="checkbox">&nbsp;Is Active<?php }
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-12" style="margin-top: 30px;">
                            <button type="submit" name="submit" style="width: 180px;margin-left: -10px" class="btn btn-primary"><i class="mdi mdi-content-save"></i>&nbsp;&nbsp;Save Settings</button>
                        </div>
                    </form>
                </div>    
            </div>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card" style="margin-top: 10px;">
                <div class="card-body">
                    <h4 class="card-title">Service Delivery Testing (Razorpay)</h4>
                    <p class="card-description">
                        Log in to your Fast2SMS account. Navigate to Settings > All settings > API Keys. If you have previously created an API key, paste the credentials over here, Kindly fo not make any changes over here if you have no knowlddge of API contact support in case of any confusion.
                    </p>
                    <form action="payment" method="get">
                        <div class="row g-3">
                            <div class="col">
                                <input type="number" class="form-control" placeholder="Amount" aria-label="api key" name="amount" required>
                            </div>
                            <div class="col-lg-3" style="margin-bottom: 20px;">
                                <select name="currency" class="form-control">
                                    <option selected value="">Select currency</option>
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
                            </div>
                            <div class="col">
                                <button type="submit" style="width: 150px;margin-left: -10px" class="btn btn-primary">Send Payment</button>
                            </div>
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