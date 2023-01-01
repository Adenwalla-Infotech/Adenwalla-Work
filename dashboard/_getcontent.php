<?php

include("../includes/_config.php");
include("../includes/_functions.php");

if (!empty($_POST["content"])) {

    if(isset($_POST['language'])){
        $language = $_POST['language'];
    }
    if(isset($_POST['tone'])){
        $tone = $_POST['tone'];
    }
    if(isset($_POST['words'])){
        $words = $_POST['words'];
    }else{
        $words = '';
    }
    if(isset($_POST['engine'])){
        $engine = $_POST['engine'];
    }
    else{
        $tone = '';
        $engine = '';
        $language = '';
    }
    $description = $_POST['desc'];
    $content = $_POST['content'];

    if($content == 'story'){
        $tool = "Provide a $tone $content in $language:";
        $type = 'text';
        $cost = costcalculation($words,$engine);
    }
    if($content == 'image'){
        if($words == 'large'){
            $words = "1024x1024";
            $cost = costcalculation(300,'text-davinci-003');
        }
        if($words == 'medium'){
            $words = "512x512";
            $cost = costcalculation(200,'text-davinci-003');
        }
        if($words == 'small'){
            $words = "256x256";
            $cost = costcalculation(100,'text-davinci-003');
        }
        $tool = "$content";
        $type = 'image';
    }else{
        $tool = "Write an $tone $content in $language:";
        $type = 'text';
        $cost = costcalculation($words,$engine);
    }
    $response = _apigeneratecontent($tool,$description,$engine,$words,$cost,$type);
    print_r($response);
}

?>