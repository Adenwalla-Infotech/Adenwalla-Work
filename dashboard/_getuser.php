<?php 
session_start();
$userid = $_SESSION['userId'];
include('../includes/_functions.php');

if(isset($_POST['param'])){
    $response = _getsingleuser($userid,$_POST['param']);
    echo $response;
}

?>