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

$_SESSION['lowbalance'] = false;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog Writer |
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
            plugins: 'wordcount fullscreen searchreplace',
            theme_advanced_buttons3_add : "fullscreen search,replace",
            fullscreen_new_window : true,
            fullscreen_settings : {
                theme_advanced_path_location : "top"
            }
            // plugins: "ExportToDoc",
            // toolbar: "ExportToDoc"
            // toolbar: 'wordcount'
        });
    </script>
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<style>
    /* body{margin-top:20px;} */
    .email-app {
        display: flex;
        flex-direction: row;
        background: #fff;
        border: 1px solid #e1e6ef;
    }

    .email-app nav {
        flex: 0 0 200px;
        padding: 1rem;
        border-right: 1px solid #e1e6ef;
    }

    .email-app nav .btn-block {
        margin-bottom: 15px;
    }

    .email-app nav .nav {
        flex-direction: column;
    }

    .email-app nav .nav .nav-item {
        position: relative;
    }

    .email-app nav .nav .nav-item .nav-link,
    .email-app nav .nav .nav-item .navbar .dropdown-toggle,
    .navbar .email-app nav .nav .nav-item .dropdown-toggle {
        color: #151b1e;
        border-bottom: 1px solid #e1e6ef;
    }

    .email-app nav .nav .nav-item .nav-link i,
    .email-app nav .nav .nav-item .navbar .dropdown-toggle i,
    .navbar .email-app nav .nav .nav-item .dropdown-toggle i {
        width: 20px;
        margin: 0 10px 0 0;
        font-size: 14px;
        text-align: center;
    }

    .email-app nav .nav .nav-item .nav-link .badge,
    .email-app nav .nav .nav-item .navbar .dropdown-toggle .badge,
    .navbar .email-app nav .nav .nav-item .dropdown-toggle .badge {
        float: right;
        margin-top: 4px;
        margin-left: 10px;
    }

    .email-app main {
        min-width: 0;
        flex: 1;
        padding: 1rem;
    }

    .email-app .inbox .toolbar {
        padding-bottom: 1rem;
        border-bottom: 1px solid #e1e6ef;
    }

    .email-app .inbox .messages {
        padding: 0;
        list-style: none;
    }

    .email-app .inbox .message {
        position: relative;
        padding: 1rem 1rem 1rem 2rem;
        cursor: pointer;
        border-bottom: 1px solid #e1e6ef;
    }

    .email-app .inbox .message:hover {
        background: #f9f9fa;
    }

    .email-app .inbox .message .actions {
        position: absolute;
        left: 0;
        display: flex;
        flex-direction: column;
    }

    .email-app .inbox .message .actions .action {
        width: 2rem;
        margin-bottom: 0.5rem;
        color: #c0cadd;
        text-align: center;
    }

    .email-app .inbox .message a {
        color: #000;
    }

    .email-app .inbox .message a:hover {
        text-decoration: none;
    }

    .email-app .inbox .message.unread .header,
    .email-app .inbox .message.unread .title {
        font-weight: bold;
    }

    .email-app .inbox .message .header {
        display: flex;
        flex-direction: row;
        margin-bottom: 0.5rem;
    }

    .email-app .inbox .message .header .date {
        margin-left: auto;
    }

    .email-app .inbox .message .title {
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .email-app .inbox .message .description {
        font-size: 12px;
    }

    .email-app .message .toolbar {
        padding-bottom: 1rem;
        border-bottom: 1px solid #e1e6ef;
    }

    .email-app .message .details .title {
        padding: 1rem 0;
        font-weight: bold;
    }

    .email-app .message .details .header {
        display: flex;
        padding: 1rem 0;
        margin: 1rem 0;
        border-top: 1px solid #e1e6ef;
        border-bottom: 1px solid #e1e6ef;
    }

    .email-app .message .details .header .avatar {
        width: 40px;
        height: 40px;
        margin-right: 1rem;
    }

    .email-app .message .details .header .from {
        font-size: 12px;
        color: #9faecb;
        align-self: center;
    }

    .email-app .message .details .header .from span {
        display: block;
        font-weight: bold;
    }

    .email-app .message .details .header .date {
        margin-left: auto;
    }

    .email-app .message .details .attachments {
        padding: 1rem 0;
        margin-bottom: 1rem;
        border-top: 3px solid #f9f9fa;
        border-bottom: 3px solid #f9f9fa;
    }

    .email-app .message .details .attachments .attachment {
        display: flex;
        margin: 0.5rem 0;
        font-size: 12px;
        align-self: center;
    }

    .email-app .message .details .attachments .attachment .badge {
        margin: 0 0.5rem;
        line-height: inherit;
    }

    .email-app .message .details .attachments .attachment .menu {
        margin-left: auto;
    }

    .email-app .message .details .attachments .attachment .menu a {
        padding: 0 0.5rem;
        font-size: 14px;
        color: #e1e6ef;
    }

    @media (max-width: 767px) {
        .email-app {
            flex-direction: column;
        }
        .email-app nav {
            flex: 0 0 100%;
        }
    }

    @media (max-width: 575px) {
        .email-app .message .header {
            flex-flow: row wrap;
        }
        .email-app .message .header .date {
            flex: 0 0 100%;
        }
    }
</style>
<body>
    <div class="container-scroller">
        <?php include('templates/_header.php'); ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <?php include('templates/_sidebar.php'); ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row" style="padding: -20px;">
                        <div class="col-lg-12 grid-margin stretch-card">
                        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
                        <br>
                        <br>
                        <div class="container bootdey">
                        <div class="email-app">
                            <nav>
                                <a href="#" class="btn btn-danger btn-block">New Email</a>
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-inbox"></i> Inbox <span class="badge badge-danger">4</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-star"></i> Stared</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-rocket"></i> Sent</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-trash-o"></i> Trash</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-bookmark"></i> Important</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-inbox"></i> Inbox <span class="badge badge-danger">4</span></a>
                                    </li>
                                </ul>
                            </nav>
                            <main>
                                <p class="text-center">New Message</p>
                                <form>
                                    <div class="form-row mb-3">
                                        <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                                        <div class="col-10 col-sm-11">
                                            <input type="email" class="form-control" id="to" placeholder="Type email">
                                        </div>
                                    </div>
                                    <div class="form-row mb-3">
                                        <label for="cc" class="col-2 col-sm-1 col-form-label">CC:</label>
                                        <div class="col-10 col-sm-11">
                                            <input type="email" class="form-control" id="cc" placeholder="Type email">
                                        </div>
                                    </div>
                                    <div class="form-row mb-3">
                                        <label for="bcc" class="col-2 col-sm-1 col-form-label">BCC:</label>
                                        <div class="col-10 col-sm-11">
                                            <input type="email" class="form-control" id="bcc" placeholder="Type email">
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-sm-11 ml-auto">
                                        <div class="toolbar" role="toolbar">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-bold"></span>
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-italic"></span>
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-underline"></span>
                                                </button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-align-left"></span>
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-align-right"></span>
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-align-center"></span>
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-align-justify"></span>
                                                </button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-indent"></span>
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-outdent"></span>
                                                </button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-list-ul"></span>
                                                </button>
                                                <button type="button" class="btn btn-light">
                                                    <span class="fa fa-list-ol"></span>
                                                </button>
                                            </div>
                                            <button type="button" class="btn btn-light">
                                                <span class="fa fa-trash-o"></span>
                                            </button>
                                            <button type="button" class="btn btn-light">
                                                <span class="fa fa-paperclip"></span>
                                            </button>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                                                    <span class="fa fa-tags"></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">add label <span class="badge badge-danger"> Home</span></a>
                                                    <a class="dropdown-item" href="#">add label <span class="badge badge-info"> Job</span></a>
                                                    <a class="dropdown-item" href="#">add label <span class="badge badge-success"> Clients</span></a>
                                                    <a class="dropdown-item" href="#">add label <span class="badge badge-warning"> News</span></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <textarea class="form-control" id="message" name="body" rows="12" placeholder="Click here to reply"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Send</button>
                                            <button type="submit" class="btn btn-light">Draft</button>
                                            <button type="submit" class="btn btn-danger">Discard</button>
                                        </div>
                                    </div>
                                </div>
                            </main>
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
            $('#success').hide();
            $('#warning').hide();
            $('#warning2').hide();
            document.getElementById("submit").onclick = function getContent(){
                $('#loader').show();
                var lan = document.getElementById('language').value
                var tone = document.getElementById('tone').value
                var words = document.getElementById("words").value
                var description = document.getElementById("description").value
                var engine = document.getElementById("engine").value
                $.ajax({
                    type: "POST",
                    url: "_getcontent.php",
                    data: 'language=' + lan + '&tone=' + tone + '&words=' + words + '&desc=' + description + '&engine=' + engine + '&content=blog',
                    success: function (data) {
                        console.log(data)
                        $('#loader').hide();
                        if(data == 0){
                            $('#warning').show();
                        }
                        if(data == 1){
                            $('#warning2').show();
                        }else{
                            tinyMCE.get('content').setContent(data);
                            $.ajax({
                            type: "POST",
                            url: "_getsession.php",
                            data: 'session=total_cost',
                            success: function (data) {
                                document.getElementById("alerttext").innerHTML = data;
                                $('#success').show();
                            }
                            });
                        }
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