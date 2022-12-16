<?php 
session_start();
if(isset($_POST['session'])){
    $session = $_POST['session'];
    echo $_SESSION[$session];
}

?>