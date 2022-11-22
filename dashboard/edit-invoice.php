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
require('../includes/_config.php');

$_id = $_GET['id'];

if (isset($_POST['submit'])) {

    $_clientname = $_POST['clientname'];
    $_clientemail = $_POST['clientemail'];
    $_clientnumber = $_POST['clientphone'];
    $_clientaddress = $_POST['clientaddress'];
    $_invoicenote = $_POST['invoicenote'];
    $_duedate = $_POST['duedate'];



    _updateInvoice($_id, $_clientname, $_clientemail, $_clientnumber, $_clientaddress, $_invoicenote, $_duedate);
}




$record_per_page = 5;
$page = '';
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $record_per_page;


if (isset($_POST['additem'])) {


    $productname = $_POST['_productname'];
    $invoicequantity = $_POST['_productquantity'];
    $invoiceamount = $_POST['_productamount'];

    _addInvoiceItem($_id, $productname, $invoicequantity, $invoiceamount);
}

if (isset($_POST['edititem'])) {


    $id = $_POST['_id'];
    $invoiceno = $_POST['_invoiceno'];
    $productname = $_POST['_productname'];
    $invoicequantity = $_POST['_productquantity'];
    $invoiceamount = $_POST['_productamount'];

    _updateInvoiceItems($id, $invoiceno, $productname, $invoicequantity, $invoiceamount);
}


if (isset($_GET['del'])) {

    $invoiceno = $_GET['invoiceno'];
    $itemid = $_GET['itemno'];

    _deleteInvoiceItems($invoiceno, $itemid);
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Invoice | <?php echo _siteconfig('_sitetitle'); ?></title>
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

                    <?php if ($_SESSION['forgot_success']) { ?>
                        <div id="liveAlertPlaceholder">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Item Deleted</strong>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Invoice</h4>
                                <p class="card-description">
                                    Before you start writing about your new topic, it's important to do some research. This will help you to understand the topic better, This will make it easier for you to write about the topic, and it will also make it more likely that people will be interested in reading what you have to say.
                                </p>
                                <form method="POST" action="" class="needs-validation" novalidate>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="clientname" class="form-label">Client Name</label>
                                            <input type="text" class="form-control" placeholder="Client name" value="<?php echo _getSingleInvoice($_id, '_clientname') ?>" aria-label="Client name" id="clientname" name="clientname" required>
                                            <div class="invalid-feedback">Please type correct client name</div>
                                        </div>
                                        <div class="col">
                                            <label for="clientemail" class="form-label">Client Email</label>
                                            <input type="email" class="form-control" placeholder="Client Email" value="<?php echo _getSingleInvoice($_id, '_clientemail') ?>" aria-label="Client Email" id="clientemail" name="clientemail" required>
                                            <div class="invalid-feedback">Please type correct membership desc</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">

                                        <div class="col">
                                            <label for="clientphone" class="form-label">Client Phone</label>
                                            <input type="text" class="form-control" placeholder="Client Phone" aria-label="Client Phone" value="<?php echo _getSingleInvoice($_id, '_clientnumber') ?>" id="clientphone" name="clientphone" required>
                                            <div class="invalid-feedback">Please type correct phone number</div>
                                        </div>
                                        <div class="col">
                                            <label for="clientaddress" class="form-label">Client Address</label>
                                            <input type="text" class="form-control" placeholder="Client Address" aria-label="Client Address" value="<?php echo _getSingleInvoice($_id, '_clientaddress') ?>" id="clientaddress" name="clientaddress" required>
                                            <div class="invalid-feedback">Please type correct address</div>
                                        </div>
                                    </div>

                                    <div class="row g-3" style="margin-top: 20px;">
                                        <div class="col">
                                            <label for="membershipdesc" class="form-label">Due Date</label>
                                            <input type="date" class="form-control" placeholder="Due Date" aria-label="Due Date" id="duedate" name="duedate" value="<?php echo _getSingleInvoice($_id, '_duedate') ?>" required>
                                            <div class="invalid-feedback">Please type correct date</div>
                                        </div>

                                        <div class="col">
                                            <label for="invoicenote" class="form-label">Note</label>
                                            <textarea name="invoicenote" rows="5" minlength="5" class="form-control" required>
                                            <?php echo _getSingleInvoice($_id, '_invoicenote') ?>
                                            </textarea>
                                        </div>
                                    </div>


                                    <div class="col-12" style="margin-top: 30px;">
                                        <button type="submit" name="submit" style="width: 200px;margin-left: -10px" class="btn btn-primary">Create Membership</button>

                                        <button type="button" class="btn btn-primary btn-sm font-weight-medium auth-form-btn" style="height:40px; float:right; " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="white" style="width: 15px;" viewBox="0 0 448 512">
                                                <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                <path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" />
                                            </svg>&nbsp;&nbsp;Add Items
                                        </button>
                                    </div>

                                </form>
                            </div>

                            <div class="card-body" style="margin-top: 30px ;">
                                <h4 class="card-title">Manage Invoice Items </h4>
                                <p class="card-description">
                                    From here, you'll see a list of all the categories on your site. You can edit or
                                    delete them from here. You can also change the order of your categories by dragging
                                    and dropping them into the order you
                                </p>
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-lg-3" style="margin-bottom: 20px;">
                                            <input type="text" class="form-control form-control-sm" name="categoryname" placeholder="Category Name">
                                        </div>
                                        <div class="col-lg-2" style="margin-bottom: 20px;">
                                            <button name="search" class="btn btn-block btn-primary btn-sm font-weight-medium auth-form-btn" style="height:40px" name="submit_search"><i class="mdi mdi-account-search"></i>&nbsp;SEARCH</button>
                                        </div>

                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="example" class="display expandable-table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Product Name</th>
                                                        <th>Product Quantity</th>
                                                        <th>Product Amount</th>
                                                        <th>Created at</th>
                                                        <th>Updated at</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: left;margin-left: 30px">
                                                    <?php
                                                    if (isset($_POST['search'])) {
                                                        _getCategory($_POST['categoryname']);
                                                    }
                                                    if (!isset($_POST['search'])) {
                                                        _getInvoiceItems($_id, $start_from, $record_per_page);
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <nav aria-label="Page navigation example" style="margin-top: 10px;">
                                    <ul class="pagination">
                                        <?php
                                        $query = mysqli_query($conn, "SELECT * FROM `tblinvoiceitems`");
                                        $total_records = mysqli_num_rows($query);
                                        $total_pages = ceil($total_records / $record_per_page);
                                        $start_loop = $page;
                                        $difference = $total_pages - $page;
                                        if ($difference <= 4) {
                                            $start_loop = $total_pages - 4;
                                        }
                                        $end_loop = $start_loop + 3;
                                        if ($page > 1) {
                                            echo "<li class='page-item'>
                        <a href='edit-invoice.php?id=$_id&page=" . ($page - 1) . "' class='page-link'>Previous</a>
                      </li>";
                                        }
                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            echo "
                      <li class='page-item'><a class='page-link' href='edit-invoice.php?id=$_id&page=" . $i . "'>$i</a></li>";
                                        }
                                        if ($page <= $end_loop) {
                                            echo "<li class='page-item'>
                        <a class='page-link' href='edit-invoice.php?id=$_id&page=" . ($page + 1) . "'>Next</a>
                      </li>";
                                        } ?>
                                    </ul>
                                </nav>
                            </div>

                        </div>

                    </div>




                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <?php include('templates/_footer.php'); ?>
                    <!-- partial -->


                    <!-- main-panel ends -->
                </div>

                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <div class="container"></div>







        <script src="../includes/_validation.js"></script>

</body>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="post">
            <div class="modal-content" style="padding: 10px;">
                <div class="modal-header" style="padding: 0px;margin-bottom: 20px;padding-bottom:10px">
                    <h4 class="modal-title fs-5" id="exampleModalLabel">Add Coupon (Custom Discount)</h4>
                    <button type="button" class="btn-close" style="border: none;;background-color:white" data-bs-dismiss="modal" aria-label="Close"><svg style="width: 15px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
                            <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                            <path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" />
                        </svg></button>
                </div>
                <div class="modal-body" style="padding: 0px;">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="inputEmail4" class="form-label">Product Name</label>
                            <input type="text" name="_productname" class="form-control" placeholder="Product Name">
                        </div>
                        <div class="col-lg-6">
                            <label for="inputEmail4" class="form-label">Product Quantity</label>
                            <input type="text" class="form-control" name="_productquantity" placeholder="Product Quantity">

                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-lg-6">
                            <label for="inputEmail4" class="form-label">Product Amount</label>
                            <input type="text" class="form-control" name="_productamount" placeholder="Product Amount">
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="padding: 0px;margin-top: 20px;padding-top:10px">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="additem" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="editItemBody">

    </div>
</div>


<script>
    const callEditItem = (invoiceno, id) => {


        $.ajax({
            type: "POST",
            url: `editInvoiceItem.php`,
            data: {
                "edit": true,
                "invoiceno": invoiceno,
                "id": id,
            },
            success: function(data) {
                $(`#editItemBody`).html(data);
                $(`#editItem`).modal("show");
            }
        });

    }
</script>

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