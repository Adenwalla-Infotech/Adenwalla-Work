<?php

include("../includes/_config.php");
include("../includes/_functions.php");

if (!empty($_POST["language"])) {

    $language = $_POST['language'];
    $tone = $_POST['tone'];
    $words = $_POST['words'];
    $description = $_POST['desc'];
    $engine = $_POST['engine'];
    $content = $_POST['content'];

    $tool = "Write an $tone $content in $language:";

    $cost = costcalculation($words,$engine);

    print_r(_apigeneratecontent($tool,$description,$engine,$words,$cost));

}

?>