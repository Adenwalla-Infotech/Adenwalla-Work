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

if (isset($_SESSION['membership_success']) || !isset($_SESSION['membership_success'])) {
    $_SESSION['membership_success'] = false;
}

if (isset($_SESSION['membership_error']) || !isset($_SESSION['membership_error'])) {
    $_SESSION['membership_error'] = false;
}


$_id = $_GET['id'];

require('../includes/_functions.php');

if (isset($_POST['submit'])) {

    $membershipname = $_POST['membershipname'];
    $membershipdesc = $_POST['membershipdesc'];
    $duration = $_POST['duration'];
    $discount = $_POST['discount'];
    $discounttype = $_POST['discounttype'];
    $price = $_POST['price'];

    if (isset($_POST['isactive'])) {
        $isactive = $_POST['isactive'];
    } else {
        $isactive = false;
    }

    _updateMembership($_id,$membershipname, $membershipdesc, $duration, $discount, $discounttype, $price, $isactive);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Membership | <?php echo _getSingleMembership($_id, '_membershipname'); ?></title>
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
        selector: '#mytextarea',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak code visualchars wordcount',
        setup: function(editor) {
	  	var max = 500;
	    editor.on('submit', function(event) {
		  var numChars = tinymce.activeEditor.plugins.wordcount.body.getCharacterCountWithoutSpaces();
		  if (numChars > max) {
			alert(`Maximum ${max} characters allowed. <br> Current Words : ${numChars} `);
			event.preventDefault();
			return false;
		  }
		});
        
        },
            branding: false,
            promotion: false
      });
    </script>
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
                <?php 
                    
                    if ($_SESSION['membership_success']) {
                        ?>
                            <div id="liveAlertPlaceholder">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Membership Created!</strong> New Membership created successfully.
                                </div>
                            </div>
                        <?php 
                    } 
                    
                    if ($_SESSION['membership_error']) {
                        ?>
                            <div id="liveAlertPlaceholder">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Membership Creation Failed!</strong> Error while creating membership.
                                </div>
                            </div>
                        <?php 
                    } 
                    
                    
                    ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Membership</h4>
                                <p class="card-description">
                                    Before you start writing about your new topic, it's important to do some research. This will help you to understand the topic better, This will make it easier for you to write about the topic, and it will also make it more likely that people will be interested in reading what you have to say.
                                </p>
                                <form method="POST" action="" class="needs-validation" novalidate>

                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label for="membershipname" class="form-label">Membership Name</label>
                                            <input type="text" class="form-control" placeholder="Membership name" value="<?php echo _getSingleMembership($_id, '_membershipname'); ?>" aria-label="Membership name" id="membershipname" name="membershipname" required>
                                            <div class="invalid-feedback">Please type correct membership name</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="duration" class="form-label">Duration(Months)</label>
                                            <select name="duration" id="duration" class="form-control" required>
                                                <?php
                                                $duration  = _getSingleMembership($_id, '_duration');

                                                for ($i = 1; $i <= 12; $i++) {

                                                    if ($duration == $i) {
                                                ?>
                                                        <option value="<?php echo $duration ?>" selected> <?php echo $duration ?> month </option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="<?php echo $i ?>"> <?php echo $i ?> month </option>
                                                <?php
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-3" style="margin-top: 10px;">
                                        <div class="col-lg-6">
                                            <label for="price" class="form-label">Membership Price</label>
                                            <input type="number" class="form-control" name="price" value="<?php echo _getSingleMembership($_id, '_price'); ?>" id="price" placeholder="Price" required>
                                            <div class="invalid-feedback">Please type correct price</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="discounttype" class="form-label">Discount Type</label>
                                            <select name="discounttype" id="duration" class="form-control">

                                                <?php

                                                $benetype = _getSingleMembership($_id, '_benefittype');

                                                if ($benetype == 'Fixed') {
                                                ?>
                                                    <option selected value="Fixed">Fixed</option>
                                                    <option value="Variable">Percentage</option>
                                                <?php
                                                }
                                                else{
                                                ?>
                                                    <option value="Fixed">Fixed</option>
                                                    <option selected value="Variable">Percentage</option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                            <div class="invalid-feedback">Please select correct discount type</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 10px;">
                                        <div class="col-lg-6">
                                            <label for="discount" class="form-label">Discount</label>
                                            <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount" value="<?php echo _getSingleMembership($_id, '_benefit'); ?>" required>
                                            <div class="invalid-feedback">Please type correct discount</div>
                                        </div>
                                        <div class="col" style="margin-top: 40px;">
                                            <label class="checkbox-inline" style="margin-left: 20px;">
                                                <?php
                                                if (_getSingleMembership($_id, '_status') == true) { ?><input name="isactive" value="true" checked type="checkbox">&nbsp;Is Active<?php }
                                                                                                                                                                                if (_getSingleMembership($_id, '_status') != true) { ?><input name="isactive" value="true" type="checkbox">&nbsp;Is Active<?php }
                                                                                                                                                                            ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col">
                                            <label for="membershipdesc" class="form-label">Membership Description</label>
                                            <textarea name="membershipdesc" id="mytextarea" style="width:100%" rows="10">
                                            <?php echo _getSingleMembership($_id, '_membershipdesc'); ?>
                                        </textarea>
                                            <div class="invalid-feedback">Please type correct membership desc</div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 200px;margin-left: -10px" class="btn btn-primary">Update Membership</button>

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

        <?php include('templates/_footer.php'); ?>





        <!-- Add Pricing Modal -->

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="post"  class="needs-validation" novalidate>
                    <div class="modal-content" style="padding: 10px;">
                        <div class="modal-header" style="padding: 0px;margin-bottom: 20px;padding-bottom:10px">
                            <h4 class="modal-title fs-5" id="exampleModalLabel">Add Pricing</h4>
                            <button type="button" class="btn-close" style="border: none;;background-color:white" data-bs-dismiss="modal" aria-label="Close"><svg style="width: 15px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                    <path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" />
                                </svg></button>
                        </div>
                        <div class="modal-body" style="padding: 0px;">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="duration" class="form-label">Duration(Months)</label>
                                    <select name="duration" id="duration" class="form-control" required>
                                        <option selected value="">Select Duration</option>

                                        <option value="1">1 month </option>
                                        <option value="2">2 month </option>
                                        <option value="3">3 month </option>
                                        <option value="4">4 month </option>
                                        <option value="5">5 month </option>
                                        <option value="6">6 month </option>
                                        <option value="7">7 month </option>
                                        <option value="8">8 month </option>
                                        <option value="9">9 month </option>
                                        <option value="10">10 month </option>
                                        <option value="11">11 month </option>
                                        <option value="12">12 month </option>

                                    </select>
                                    <div class="invalid-feedback">Please select correct duration</div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" name="price" id="price" placeholder="Price" required>
                                    <div class="invalid-feedback">Please type correct price</div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-lg-6">
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount" required>
                                    <div class="invalid-feedback">Please type correct discount</div>
                                </div>


                                <div class="col-lg-6" style="margin-top: 40px;">
                                    <label class="checkbox-inline" style="margin-left: 5px;">
                                        <input name="isactive" value="true" type="checkbox"> &nbsp; Is Active
                                    </label>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-lg-6">
                                    <label for="discounttype" class="form-label">Discount Type</label>
                                    <select name="discounttype" id="duration" class="form-control">
                                        <option selected value="">Discount Type</option>
                                        <option value="Fixed">Fixed</option>
                                        <option value="Variable">Percentage</option>
                                    </select>
                                    <div class="invalid-feedback">Please select correct discount type</div>

                                </div>
                                <div class="col-lg-6">
                                    <label for="discountcurrency" class="form-label">Discount Currency</label>
                                    <select name="discountcurrency" class="form-control" required>
                                        <option selected disabled value="">Select currency</option>
                                        <option value="USD">America (United States) Dollars ??? USD</option>
                                        <option value="AFN">Afghanistan Afghanis ??? AFN</option>
                                        <option value="ALL">Albania Leke ??? ALL</option>
                                        <option value="DZD">Algeria Dinars ??? DZD</option>
                                        <option value="ARS">Argentina Pesos ??? ARS</option>
                                        <option value="AUD">Australia Dollars ??? AUD</option>
                                        <option value="ATS">Austria Schillings ??? ATS</OPTION>

                                        <option value="BSD">Bahamas Dollars ??? BSD</option>
                                        <option value="BHD">Bahrain Dinars ??? BHD</option>
                                        <option value="BDT">Bangladesh Taka ??? BDT</option>
                                        <option value="BBD">Barbados Dollars ??? BBD</option>
                                        <option value="BEF">Belgium Francs ??? BEF</OPTION>
                                        <option value="BMD">Bermuda Dollars ??? BMD</option>

                                        <option value="BRL">Brazil Reais ??? BRL</option>
                                        <option value="BGN">Bulgaria Leva ??? BGN</option>
                                        <option value="CAD">Canada Dollars ??? CAD</option>
                                        <option value="XOF">CFA BCEAO Francs ??? XOF</option>
                                        <option value="XAF">CFA BEAC Francs ??? XAF</option>
                                        <option value="CLP">Chile Pesos ??? CLP</option>

                                        <option value="CNY">China Yuan Renminbi ??? CNY</option>
                                        <option value="CNY">RMB (China Yuan Renminbi) ??? CNY</option>
                                        <option value="COP">Colombia Pesos ??? COP</option>
                                        <option value="XPF">CFP Francs ??? XPF</option>
                                        <option value="CRC">Costa Rica Colones ??? CRC</option>
                                        <option value="HRK">Croatia Kuna ??? HRK</option>

                                        <option value="CYP">Cyprus Pounds ??? CYP</option>
                                        <option value="CZK">Czech Republic Koruny ??? CZK</option>
                                        <option value="DKK">Denmark Kroner ??? DKK</option>
                                        <option value="DEM">Deutsche (Germany) Marks ??? DEM</OPTION>
                                        <option value="DOP">Dominican Republic Pesos ??? DOP</option>
                                        <option value="NLG">Dutch (Netherlands) Guilders ??? NLG</OPTION>

                                        <option value="XCD">Eastern Caribbean Dollars ??? XCD</option>
                                        <option value="EGP">Egypt Pounds ??? EGP</option>
                                        <option value="EEK">Estonia Krooni ??? EEK</option>
                                        <option value="EUR">Euro ??? EUR</option>
                                        <option value="FJD">Fiji Dollars ??? FJD</option>
                                        <option value="FIM">Finland Markkaa ??? FIM</OPTION>

                                        <option value="FRF*">France Francs ??? FRF*</OPTION>
                                        <option value="DEM">Germany Deutsche Marks ??? DEM</OPTION>
                                        <option value="XAU">Gold Ounces ??? XAU</option>
                                        <option value="GRD">Greece Drachmae ??? GRD</OPTION>
                                        <option value="GTQ">Guatemalan Quetzal ??? GTQ</OPTION>
                                        <option value="NLG">Holland (Netherlands) Guilders ??? NLG</OPTION>
                                        <option value="HKD">Hong Kong Dollars ??? HKD</option>

                                        <option value="HUF">Hungary Forint ??? HUF</option>
                                        <option value="ISK">Iceland Kronur ??? ISK</option>
                                        <option value="XDR">IMF Special Drawing Right ??? XDR</option>
                                        <option value="INR">India Rupees ??? INR</option>
                                        <option value="IDR">Indonesia Rupiahs ??? IDR</option>
                                        <option value="IRR">Iran Rials ??? IRR</option>

                                        <option value="IQD">Iraq Dinars ??? IQD</option>
                                        <option value="IEP*">Ireland Pounds ??? IEP*</OPTION>
                                        <option value="ILS">Israel New Shekels ??? ILS</option>
                                        <option value="ITL*">Italy Lire ??? ITL*</OPTION>
                                        <option value="JMD">Jamaica Dollars ??? JMD</option>
                                        <option value="JPY">Japan Yen ??? JPY</option>

                                        <option value="JOD">Jordan Dinars ??? JOD</option>
                                        <option value="KES">Kenya Shillings ??? KES</option>
                                        <option value="KRW">Korea (South) Won ??? KRW</option>
                                        <option value="KWD">Kuwait Dinars ??? KWD</option>
                                        <option value="LBP">Lebanon Pounds ??? LBP</option>
                                        <option value="LUF">Luxembourg Francs ??? LUF</OPTION>

                                        <option value="MYR">Malaysia Ringgits ??? MYR</option>
                                        <option value="MTL">Malta Liri ??? MTL</option>
                                        <option value="MUR">Mauritius Rupees ??? MUR</option>
                                        <option value="MXN">Mexico Pesos ??? MXN</option>
                                        <option value="MAD">Morocco Dirhams ??? MAD</option>
                                        <option value="NLG">Netherlands Guilders ??? NLG</OPTION>

                                        <option value="NZD">New Zealand Dollars ??? NZD</option>
                                        <option value="NOK">Norway Kroner ??? NOK</option>
                                        <option value="OMR">Oman Rials ??? OMR</option>
                                        <option value="PKR">Pakistan Rupees ??? PKR</option>
                                        <option value="XPD">Palladium Ounces ??? XPD</option>
                                        <option value="PEN">Peru Nuevos Soles ??? PEN</option>

                                        <option value="PHP">Philippines Pesos ??? PHP</option>
                                        <option value="XPT">Platinum Ounces ??? XPT</option>
                                        <option value="PLN">Poland Zlotych ??? PLN</option>
                                        <option value="PTE">Portugal Escudos ??? PTE</OPTION>
                                        <option value="QAR">Qatar Riyals ??? QAR</option>
                                        <option value="RON">Romania New Lei ??? RON</option>

                                        <option value="ROL">Romania Lei ??? ROL</option>
                                        <option value="RUB">Russia Rubles ??? RUB</option>
                                        <option value="SAR">Saudi Arabia Riyals ??? SAR</option>
                                        <option value="XAG">Silver Ounces ??? XAG</option>
                                        <option value="SGD">Singapore Dollars ??? SGD</option>
                                        <option value="SKK">Slovakia Koruny ??? SKK</option>

                                        <option value="SIT">Slovenia Tolars ??? SIT</option>
                                        <option value="ZAR">South Africa Rand ??? ZAR</option>
                                        <option value="KRW">South Korea Won ??? KRW</option>
                                        <option value="ESP">Spain Pesetas ??? ESP</OPTION>
                                        <option value="XDR">Special Drawing Rights (IMF) ??? XDR</option>
                                        <option value="LKR">Sri Lanka Rupees ??? LKR</option>

                                        <option value="SDD">Sudan Dinars ??? SDD</option>
                                        <option value="SEK">Sweden Kronor ??? SEK</option>
                                        <option value="CHF">Switzerland Francs ??? CHF</option>
                                        <option value="TWD">Taiwan New Dollars ??? TWD</option>
                                        <option value="THB">Thailand Baht ??? THB</option>
                                        <option value="TTD">Trinidad and Tobago Dollars ??? TTD</option>

                                        <option value="TND">Tunisia Dinars ??? TND</option>
                                        <option value="TRY">Turkey New Lira ??? TRY</option>
                                        <option value="AED">United Arab Emirates Dirhams ??? AED</option>
                                        <option value="GBP">United Kingdom Pounds ??? GBP</option>
                                        <option value="USD">United States Dollars ??? USD</option>
                                        <option value="VEB">Venezuela Bolivares ??? VEB</option>

                                        <option value="VND">Vietnam Dong ??? VND</option>
                                        <option value="ZMK">Zambia Kwacha ??? ZMK</option>
                                    </select>
                                    <div class="invalid-feedback">Please select correct currency</div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer" style="padding: 0px;margin-top: 20px;padding-top:10px">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="addpricing" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <!-- Edit Modal -->



        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

            <div class="modal-dialog" id="displayData">

            </div>
        </div>


        <script src="../includes/_validation.js"></script>

</body>
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</html>