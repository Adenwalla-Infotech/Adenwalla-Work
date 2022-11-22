<?php

require("../includes/_config.php");
require('../includes/_functions.php');


if (isset($_POST['edit'])) {

    $invoiceno = $_POST['invoiceno'];
    $id = $_POST['id'];





?>


    <form action="" method="post">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header" style="padding: 0px;margin-bottom: 20px;padding-bottom:10px">
                <h4 class="modal-title fs-5" id="exampleModalLabel">Edit Item</h4>
                <button type="button" class="btn-close" style="border: none;;background-color:white" data-bs-dismiss="modal" aria-label="Close"><svg style="width: 15px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
                        <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                        <path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" />
                    </svg></button>
            </div>
            <div class="modal-body" style="padding: 0px;">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="inputEmail4" class="form-label">Product Name</label>
                        <input type="text" name="_productname" value="<?php echo _getSingleInvoiceItem($invoiceno, $id, '_productname') ?>" class="form-control" placeholder="Product Name">
                    </div>
                    <div class="col-lg-6">
                        <label for="inputEmail4" class="form-label">Product Quantity</label>
                        <input type="text" class="form-control" name="_productquantity" value="<?php echo _getSingleInvoiceItem($invoiceno, $id, '_productquantity') ?>" placeholder="Product Quantity">

                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-lg-6">
                        <label for="inputEmail4" class="form-label">Product Amount</label>
                        <input type="text" class="form-control" name="_productamount" value="<?php echo _getSingleInvoiceItem($invoiceno, $id, '_productamount') ?>" placeholder="Product Amount">
                    </div>
                </div>
                <div class="row" style="margin-top: 20px; display: none; ">
                    <div class="col-lg-6">
                        <label for="inputEmail4" class="form-label">Product Amount</label>
                        <input type="text" class="form-control" name="_invoiceno" value="<?php echo _getSingleInvoiceItem($invoiceno, $id, '_invoiceno') ?>" placeholder="Product Amount">
                    </div>
                </div>
                <div class="row" style="margin-top: 20px; display:none ; ">
                    <div class="col-lg-6">
                        <label for="inputEmail4" class="form-label">Product Amount</label>
                        <input type="text" class="form-control" name="_id" value="<?php echo _getSingleInvoiceItem($invoiceno, $id, '_id') ?>" placeholder="Product Amount">
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="padding: 0px;margin-top: 20px;padding-top:10px">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="edititem" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>

<?php




}





?>