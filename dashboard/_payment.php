<?php 
require('../includes/_functions.php');

$record_per_page = 5;
$page = '';
if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
if (isset($_GET["start"])) {
  $start_from = $_GET["start"];
}
echo _getTranscations('', '', '', $start_from ,$record_per_page);    

?>