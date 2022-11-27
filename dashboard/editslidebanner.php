<?php

require("../includes/_config.php");
require('../includes/_functions.php');


if (isset($_POST['edit'])) {

    $courseid = $_POST['courseid'];
    $slideid = $_POST['slideid'];





?>


<form action="" method="post" enctype="multipart/form-data" >
    <div class="modal-content" style="padding: 10px;">
        <div class="modal-header" style="padding: 0px;margin-bottom: 20px;padding-bottom:10px">
            <h4 class="modal-title fs-5" id="exampleModalLabel">Edit Slide</h4>
            <button type="button" class="btn-close" style="border: none;;background-color:white" data-bs-dismiss="modal"
                aria-label="Close"><svg style="width: 15px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                    <path
                        d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" />
                </svg></button>
        </div>

        <div class="modal-body" style="padding: 0px;">

            <div class="row">
                <div class="col-lg-12">
                    <label for="inputEmail4" class="form-label">Banner Image</label>
                    <input type="file" name="banner" class="form-control" style="margin-bottom: 20px;">
                    <a href="../uploads/banner/<?php echo  _getSingleSlide($slideid, $courseid ,'_slideurl'); ?>" target="_blank"  >
                    Open
                        Featured Image &nbsp;<svg xmlns="http://www.w3.org/2000/svg" style="width: 15px;"
                            viewBox="0 0 512 512">
                            <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                            <path
                                d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z" />
                        </svg>
                    </a>
                </div>

            </div>

            <div class="row" style="margin-top: 30px;">
                <div class="col">
                    <label for="caption" class="form-label">Caption</label>
                    <textarea name="caption" id="caption" style="width:100%" rows="10">
                        <?php echo  _getSingleSlide($slideid, $courseid ,'_caption');?>
                    </textarea>
                    <div class="invalid-feedback">Please type caption</div>
                </div>
                <div class="col-lg-6" style="display: none;">

                    <input type="text" name="courseid" value="<?php echo $courseid ?>">

                </div>
                <div class="col-lg-6" style="display: none;">

                    <input type="text" name="slideid" value="<?php echo $slideid ?>">

                </div>
            </div>

        </div>
        <div class="modal-footer" style="padding: 0px;margin-top: 20px;padding-top:10px">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="editSlide" class="btn btn-primary">Save changes</button>
        </div>
    </div>
</form>

<?php




}





?>