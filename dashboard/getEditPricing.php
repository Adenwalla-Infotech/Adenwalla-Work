<?php

require("../includes/_config.php");

if (isset($_POST['edit'])) {

    $id = $_POST['value'];

    $sql = "SELECT * FROM `tblmembershippricing` WHERE `_id`='$id' ";

    $query = mysqli_query($conn, $sql);

    if ($query) {

        foreach ($query as $data) {


            echo $return = '
                <form action="" method="post" class="needs-validation" novalidate>

                    <div class="modal-content" style="padding: 10px;">
                        <div class="modal-header" style="padding: 0px;margin-bottom: 20px;padding-bottom:10px">
                            <h4 class="modal-title fs-5" id="exampleModalLabel">Edit Pricing </h4>
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
                                    <option selected value="'.$data['_duration'].'" >'.$data['_duration'].'</option>

                                    <option value="1 month">1 month </option>
                                    <option value="2 month">2 month </option>
                                    <option value="3 month">3 month </option>
                                    <option value="4 month">4 month </option>
                                    <option value="5 month">5 month </option>
                                    <option value="6 month">6 month </option>
                                    <option value="7 month">7 month </option>
                                    <option value="8 month">8 month </option>
                                    <option value="9 month">9 month </option>
                                    <option value="10 month">10 month </option>
                                    <option value="11 month">11 month </option>
                                    <option value="12 month">12 month </option>


                                    </select>
                                    <div class="invalid-feedback">Please select correct duration</div>
                                   
                                </div>
                                <div class="col-lg-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" value="'.$data['_price'].'"   name="price" id="price" placeholder="Price" required>
                                    <div class="invalid-feedback">Please type correct price</div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-lg-6">
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="discount" value="'.$data['_benefit'].'" name="discount" placeholder="Discount" required>
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
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;">
                                    <div class="col-lg-6">
                                        <label for="discounttype" class="form-label">Discount Type</label>
                                        <select name="discounttype" id="duration" class="form-control" required>
                                            <option selected value="'.$data['_benefittype'].'">'.$data['_benefittype'].'</option>
                                            <option value="static">Static</option>
                                            <option value="percentage">Percentage %</option>
                                        </select>
                                        <div class="invalid-feedback">Please select correct discount type</div>
                                    </div>
                                <div class="col-lg-6">
                                    <label for="discountcurrency" class="form-label">Discount Currency</label>
                                    <select name="discountcurrency" class="form-control" required>
                                        
                                        <option selected value="'.$data['_benefitcurrency'].'">'.$data['_benefitcurrency'].'</option>

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
                                    <div class="invalid-feedback">Please select correct currency</div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer" style="padding: 0px;margin-top: 20px;padding-top:10px">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="editpricing" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
       

                ';
        }
    }
}
