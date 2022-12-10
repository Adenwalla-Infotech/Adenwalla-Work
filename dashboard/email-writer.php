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

include('../includes/_functions.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Email Writer |
        <?php echo _siteconfig('_sitetitle'); ?>
    </title>
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
            selector: '#content',
            branding: false,
            promotion: false,
            plugins: 'wordcount',
            toolbar: 'wordcount'
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
                    <div class="row">
                        <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                <!-- <h4 class="card-title">Email Writer ( AI Email Creation)</h4>
                                <p class="card-description">
                                    If you can't find a solution to your problems in our knowledgebase, you can submit a
                                    ticket by selecting the appropriate department below & subject below. Tickets can
                                    also be created by simply sending an email. Ticket responses can also be created by
                                    replying to the same email.
                                </p> -->
                                    <div class="row">
                                        <div class="col-lg-12" style="margin-bottom: 20px;">
                                        <label>Select Language</label>
                                        <select class="form-control" id="language">
                                            <option>Select Language</option>
                                            <option value="english">English</option>
                                            <option value="hindi">Hindi</option>
                                            <option value="marathi">Marathi</option>
                                            <option value="gujarati">Gujarati</option>
                                            <option value="arabic">Arabic</option>
                                            <option value="bulgarian">Bulgarian</option>
                                            <option value="farsi">Farsi</option>
                                            <option value="filipino">Filipino</option>
                                            <option value="finnish">Finnish</option>
                                            <option value="french">French</option>
                                            <option value="german">German</option>
                                            <option value="greek">Greek</option>
                                            <option value="hebrew">Hebrew</option>
                                            <option value="hungarian">Hungarian</option>
                                            <option value="indonesian">Indonesian</option>
                                            <option value="italian">Italian</option>
                                            <option value="japanese">Japanese</option>
                                            <option value="korean">Korean</option>
                                            <option value="lithuanian">Lithuanian</option>
                                            <option value="malay">Malay</option>
                                            <option value="polish">Polish</option>
                                            <option value="portuguese">Portuguese</option>
                                            <option value="russian">Russian</option>
                                            <option value="slovak">Slovak</option>
                                            <option value="slovenian">Slovenian</option>
                                            <option value="spanish">Spanish</option>
                                            <option value="swedish">Swedish</option>
                                            <option value="thai">Thai</option>
                                            <option value="turkish">Turkish</option>
                                            <option value="romanian">Romanian</option>
                                            <option value="ukrainian">Ukrainian</option>
                                            <option value="vietnamese">Vietnamese</option>
                                        </select>
                                        </div>
                                        <div class="col-lg-12" style="margin-bottom: 20px;">
                                        <label>Select Content Tone</label>
                                        <select class="form-control" id="tone">
                                            <option>Select Tone</option>
                                            <option value="appreciative">Appreciative</option>
                                            <option value="assertive">Assertive</option>
                                            <option value="awestruck">Awestruck</option>
                                            <option value="candid">Candid</option>
                                            <option value="casual">Casual</option>
                                            <option value="cautionary">Cautionary</option>
                                            <option value="compassionate">Compassionate</option>
                                            <option value="convincing">Convincing</option>
                                            <option value="critical">Critical</option>
                                            <option value="earnest">Earnest</option>
                                            <option value="enthusiastic">Enthusiastic</option>
                                            <option value="formal">Formal</option>
                                            <option value="funny">Funny</option>
                                            <option value="humble">Humble</option>
                                            <option value="humorous">Humorous</option>
                                            <option value="informative">Informative</option>
                                            <option value="inspirational">Inspirational</option>
                                            <option value="joyful">Joyful</option>
                                            <option value="passionate">Passionate</option>
                                            <option value="thoughtful">Thoughtful</option>
                                            <option value="urgent">Urgent</option>
                                            <option value="worried">Worried</option>
                                        </select>
                                        </div>
                                        <div class="col-lg-12" style="margin-bottom: 20px;">
                                        <label>Maximum Words</label><br>
                                        <input id="words" style="width: 100%;" type="range" class="form-range" min="50" max="4000" step="1"><br>
                                        <output id="output" style="color: grey;">0</output>&nbsp;<span style="color: grey;">Word</span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 20px;">
                                    <div class="col-lg-12">
                                        <label>Describe Content (Short Summary)</label><br>
                                        <textarea id="description" style="width:100%;line-height: 1.25; font-size: 15px; margin: 0; padding: 15px;" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 20px;">
                                        <label>Select Engine</label><br>
                                        <select id="engine" class="form-control">
                                            <option value="text-ada-001">Basic Engine</option>
                                            <option value="text-babbage-001">Premium Engine</option>
                                            <option value="text-curie-001">Elementry Engine</option>
                                            <option value="text-davinci-003">Advanced Engine</option>
                                        </select>
                                    </div>
                                    </div>
                                    <div class="row" style="margin-top: 30px;">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" style="border-radius: 6px;width:100%" id="submit"><div style="width:20px;height:20px;margin-right:10px" class="spinner-border text-light" id="loader" role="status">
                                            <span class="visually-hidden"></span>
                                        </div> Generate Email</button>
                                    </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div id="alert" class="alert alert-success" role="alert">
                                        Successfull <strong id="alerttext"></strong> Balance Consumed
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <textarea id="content" class="form-control" style="width: 100%;" rows="25"></textarea>
                                        </div>
                                    </div>
                                </div>
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
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script>
            $('#loader').hide();
            $('#alert').hide();
            document.getElementById("submit").onclick = function getContent(){
                $('#loader').show();
                var lan = document.getElementById('language').value
                var tone = document.getElementById('tone').value
                var words = document.getElementById("words").value
                var description = document.getElementById("description").value
                var engine = document.getElementById("engine").value
                $.ajax({
                    type: "POST",
                    url: "getContent.php",
                    data: 'language=' + lan + '&tone=' + tone + '&words=' + words + '&desc=' + description + '&engine=' + engine + '&content=email',
                    success: function (data) {
                        console.log(data)
                        tinyMCE.get('content').setContent(data);
                        $('#loader').hide();
                        $.ajax({
                            type: "POST",
                            url: "getSession.php",
                            data: 'session=total_cost',
                            success: function (data) {
                                console.log(data)
                                document.getElementById("alerttext").innerHTML = data;
                                $('#alert').show();
                            }
                        });
                    }
                });
            }
            document.getElementById("words").oninput = function() {
                myFunction()
            };

            function myFunction() {
            var val = document.getElementById("words").value //gets the oninput value
            document.getElementById('output').innerHTML = val //displays this value to the html page
            }

        </script>

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