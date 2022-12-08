<?php

require('../includes/_functions.php');
require('../includes/_config.php');

$invoiceno = $_GET['invoiceno'];

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <div class="page-content container" style="margin-bottom: 50px;">
        <div class="page-header text-blue-d2">
            <h1 class="page-title text-secondary-d1">
                Invoice
                <small class="page-info">
                    <i class="fa fa-angle-double-right text-80"></i>
                    ID: <?php echo _getSingleInvoice($invoiceno, '_refno') ?>
                </small>
            </h1>

            <div class="page-tools">
                <div class="action-buttons">
                    <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="Print" onclick="window.print()">
                        <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                        Print
                    </a>
                </div>
            </div>
        </div>

        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center text-150">
                                <img style="width: 180px;" src="../uploads/images/<?php echo _siteconfig('_sitelogo'); ?>" alt="logo" />
                            </div>
                        </div>
                    </div>
                    <!-- .row -->

                    <hr class="row brc-default-l1 mx-n1 mb-4" />

                    <div class="row">
                        <div class="col-sm-6">
                            <div>
                                <span class="text-sm text-grey-m2 align-middle">To:</span>
                                <span class="text-600 text-110 text-blue align-middle"><?php echo _getSingleInvoice($invoiceno, '_clientname') ?></span>
                            </div>
                            <div class="text-grey-m2">
                                <div class="my-1">
                                    <?php echo _getSingleInvoice($invoiceno, '_clientaddress') ?>
                                </div>
                                <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600"> <?php echo _getSingleInvoice($invoiceno, '_clientnumber') ?></b></div>
                                <div class="my-1">
                                    <?php echo _getSingleInvoice($invoiceno, '_clientemail') ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none" />
                            <div class="text-grey-m2">
                                <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                    Invoice
                                </div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">ID:</span> <?php echo _getSingleInvoice($invoiceno, '_refno') ?></div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Due Date:</span> <?php echo _getSingleInvoice($invoiceno, '_duedate') ?></div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Status:</span>



                                    <?php

                                    $status =  _getSingleInvoice($invoiceno, '_paymentstatus');

                                    if ($status == 'UnPaid') {
                                    ?>
                                        <span class="badge badge-danger badge-pill px-25">Unpaid</span>
                                    <?php
                                    } else {
                                    ?>
                                        <span class="badge badge-success badge-pill px-25">Paid</span>
                                    <?php
                                    }
                                    ?>

                                </div>

                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="mt-4">
                        <div class="row text-600 text-white bgc-default-tp1 py-25">
                            <div class="d-none d-sm-block col-1">#</div>
                            <div class="col-9 col-sm-5">Description</div>
                            <div class="d-none d-sm-block col-4 col-sm-2">Qty</div>
                            <div class="d-none d-sm-block col-sm-2">Unit Price</div>
                            <div class="col-2">Amount</div>
                        </div>

                        <?php
                        $sql = "SELECT * FROM `tblinvoiceitems` WHERE `_invoiceno` = '$invoiceno' ";
                        $query = mysqli_query($conn, $sql);
                        foreach ($query as $index => $data) {
                            $productName =  $data['_productname'];
                            $productQuantity =  $data['_productquantity'];
                            $productRate =  $data['_productamount'];
                            $total = (int)$productQuantity * (int)$productRate;
                        ?>
                            <div class="text-95 text-secondary-d3">
                                <div class="row mb-2 mb-sm-0 py-25">
                                    <div class="d-none d-sm-block col-1"> <?php echo $index + 1 ?>) </div>
                                    <div class="col-9 col-sm-5"><?php echo $productName ?></div>
                                    <div class="d-none d-sm-block col-2"><?php echo $productQuantity ?></div>
                                    <div class="d-none d-sm-block col-2 text-95">INR&nbsp;<?php echo $productRate ?></div>
                                    <div class="col-2 text-secondary-d2">INR&nbsp;<span id="totalAmount"><?php echo $total ?></span></div>
                                </div>
                            </div>
                        <?php
                        }


                        ?>

                        <div class="row border-b-2 brc-default-l2"></div>

                        <!-- or use a table instead -->
                        <!--
            <div class="table-responsive">
                <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                    <thead class="bg-none bgc-default-tp1">
                        <tr class="text-white">
                            <th class="opacity-2">#</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th width="140">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="text-95 text-secondary-d3">
                        <tr></tr>
                        <tr>
                            <td>1</td>
                            <td>Domain registration</td>
                            <td>2</td>
                            <td class="text-95">$10</td>
                            <td class="text-secondary-d2">$20</td>
                        </tr> 
                    </tbody>
                </table>
            </div>
            -->

                        <div class="row mt-3">
                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                <?php echo _getSingleInvoice($invoiceno, '_invoicenote') ?>
                            </div>

                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">


                                <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                    <div class="col-7 text-right">
                                        Total Amount
                                    </div>
                                    <div class="col-5">
                                        INR&nbsp;<span class="text-150 text-success-d3 opacity-2" id="amountDisplay"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($status != 'paid'){ ?>

                        <hr />

                        <div style="margin-bottom: 50px;">
                            <div class="row g-3">
                                <!-- <div class="col">
                                    <span class="text-secondary-d1 text-105">Thank you for your business</span>

                                </div> -->

                                <div>

                                    <div class="col">
                                        <!-- <label for="usertype" class="form-label">Select Currency</label> -->
                                        <select onchange="setConversionCurrency(this.options[this.selectedIndex])" style="height: 46px;" id="usertype" name="usertype" class="form-control form-control-sm" id="exampleFormControlSelect2" required>
                                            <option selected disabled value="">Chose Payment Currency</option>
                                            <?php _getmarkupOnlyCurrency() ?>
                                        </select>
                                        <div class="invalid-feedback">Please select correct usertype</div>
                                    </div>

                                </div>

                                <div class="col">
                                    <a id="payNow" class="btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0">Pay Now</a>
                                </div>
                            </div>

                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        body {
            margin-top: 20px;
            color: #484b51;
        }

        .text-secondary-d1 {
            color: #728299 !important;
        }

        .page-header {
            margin: 0 0 1rem;
            padding-bottom: 1rem;
            padding-top: .5rem;
            border-bottom: 1px dotted #e2e2e2;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -ms-flex-align: center;
            align-items: center;
        }

        .page-title {
            padding: 0;
            margin: 0;
            font-size: 1.75rem;
            font-weight: 300;
        }

        .brc-default-l1 {
            border-color: #dce9f0 !important;
        }

        .ml-n1,
        .mx-n1 {
            margin-left: -.25rem !important;
        }

        .mr-n1,
        .mx-n1 {
            margin-right: -.25rem !important;
        }

        .mb-4,
        .my-4 {
            margin-bottom: 1.5rem !important;
        }

        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, .1);
        }

        .text-grey-m2 {
            color: #888a8d !important;
        }

        .text-success-m2 {
            color: #86bd68 !important;
        }

        .font-bolder,
        .text-600 {
            font-weight: 600 !important;
        }

        .text-110 {
            font-size: 110% !important;
        }

        .text-blue {
            color: #478fcc !important;
        }

        .pb-25,
        .py-25 {
            padding-bottom: .75rem !important;
        }

        .pt-25,
        .py-25 {
            padding-top: .75rem !important;
        }

        .bgc-default-tp1 {
            background-color: rgba(121, 169, 197, .92) !important;
        }

        .bgc-default-l4,
        .bgc-h-default-l4:hover {
            background-color: #f3f8fa !important;
        }

        .page-header .page-tools {
            -ms-flex-item-align: end;
            align-self: flex-end;
        }

        .btn-light {
            color: #757984;
            background-color: #f5f6f9;
            border-color: #dddfe4;
        }

        .w-2 {
            width: 1rem;
        }

        .text-120 {
            font-size: 120% !important;
        }

        .text-primary-m1 {
            color: #4087d4 !important;
        }

        .text-danger-m1 {
            color: #dd4949 !important;
        }

        .text-blue-m2 {
            color: #68a3d5 !important;
        }

        .text-150 {
            font-size: 150% !important;
        }

        .text-60 {
            font-size: 60% !important;
        }

        .text-grey-m1 {
            color: #7b7d81 !important;
        }

        .align-bottom {
            vertical-align: bottom !important;
        }
    </style>

    <script type="text/javascript">
        let totalAmount = document.querySelectorAll("#totalAmount");
        let amountDisplay = document.getElementById("amountDisplay");

        let total = 0;

        for (let i = 0; i < totalAmount.length; i++) {

            total = total + parseFloat(totalAmount[i].innerHTML);

        }

        amountDisplay.innerHTML = total;

        let payNow = document.getElementById('payNow');




        const setConversionCurrency = (value) => {

            let currency = value.value;

            payNow.setAttribute("href", `payment?amount=${total}&currency=${currency}&prod=invoice&id=<?php echo $invoiceno; ?>`)



        }
    </script>
</body>

</html>