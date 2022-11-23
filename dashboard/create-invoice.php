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

if (isset($_SESSION['forgot_success']) || !isset($_SESSION['forgot_success'])) {
    $_SESSION['forgot_success'] = false;
}

require('../includes/_functions.php');

if (isset($_POST['submit'])) {

    $_clientname = $_POST['clientname'];
    $_clientemail = $_POST['clientemail'];
    $_clientnumber = $_POST['clientphone'];
    $_clientaddress = $_POST['clientaddress'];
    $_invoicenote = $_POST['invoicenote'];
    $_refno = $_POST['refno'];
    $_duedate = $_POST['duedate'];
    $_paymentstatus = $_POST['paymentstatus'];



    _createInvoice($_clientname, $_clientemail, $_clientnumber, $_clientaddress, $_invoicenote, $_refno, $_duedate,$_paymentstatus);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Invoice | <?php echo _siteconfig('_sitetitle'); ?></title>
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
    <script src="../assets/plugins/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea'
        });
    </script>
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
                    <?php if ($_SESSION['forgot_success']) { ?>
                        <div id="liveAlertPlaceholder">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Invoice Created!</strong> New Invoice created successfully.
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create Invoice (New Invoice)</h4>
                                <p class="card-description">
                                    Before you start writing about your new topic, it's important to do some research. This will help you to understand the topic better, This will make it easier for you to write about the topic, and it will also make it more likely that people will be interested in reading what you have to say.
                                </p>
                                <form method="POST" action="" class="needs-validation" novalidate>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="clientname" class="form-label">Client Name</label>
                                            <input type="text" class="form-control" placeholder="Client name" aria-label="Client name" id="clientname" name="clientname" required>
                                            <div class="invalid-feedback">Please type correct client name</div>
                                        </div>
                                        <div class="col">
                                            <label for="clientemail" class="form-label">Client Email</label>
                                            <input type="email" class="form-control" placeholder="Client Email" aria-label="Client Email" id="clientemail" name="clientemail" required>
                                            <div class="invalid-feedback">Please type correct membership desc</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="clientphone" class="form-label">Client Phone</label>
                                            <input type="text" class="form-control" placeholder="Client Phone" aria-label="Client Phone" id="clientphone" name="clientphone" required>
                                            <div class="invalid-feedback">Please type correct phone number</div>
                                        </div>
                                        <div class="col">
                                            <label for="clientaddress" class="form-label">Client Address</label>
                                            <input type="text" class="form-control" placeholder="Client Address" aria-label="Client Address" id="clientaddress" name="clientaddress" required>
                                            <div class="invalid-feedback">Please type correct address</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="membershipdesc" class="form-label">Due Date</label>
                                            <input type="date" class="form-control" placeholder="Due Date" aria-label="Due Date" id="duedate" name="duedate" required>
                                            <div class="invalid-feedback">Please type correct date</div>
                                        </div>

                                        <div class="col">
                                            <label for="membershipdesc" class="form-label">Invoice No</label>
                                            <input type="text" class="form-control" placeholder="Invoice No" aria-label="Invoice No" id="refno" name="refno" required>
                                            <div class="invalid-feedback">Please type correct Invoice No</div>
                                        </div>

                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col-6">
                                            <label for="paymentstatus" class="form-label">Payment Status</label>
                                            <select style="height: 46px;" id="paymentstatus" name="paymentstatus" class="form-control form-control-lg"  required>
                                                <option selected disabled value="">Status</option>
                                                <option value="UnPaid">UnPaid</option>
                                                <option value="Paid">Paid</option>
                                            </select>
                                            <div class="invalid-feedback">Please select correct status</div>
                                        </div>

                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="invoicenote" class="form-label">Extra Instruction</label>
                                            <textarea name="invoicenote" id="mytextarea" rows="5" minlength="5" class="form-control" required></textarea>
                                        </div>
                                    </div>


                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 200px;margin-left: -10px" class="btn btn-primary">Create Membership</button>

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