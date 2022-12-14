<?php
    session_start();
    require('../includes/_functions.php');
    
    if(isset($_POST['payment'])){
        $tranid = $_SESSION['transid'];
        $couponid = $_SESSION['couponid'];
        $userid = $_SESSION['userId'];
        $amount = $_SESSION['recamt'];
        if(isset($_POST['product'])&& isset($_POST['productid'])){
            $product = $_POST['product'];
            $productid = $_POST['productid'];
            if($product == 'membership'){
                _purchasememebership($userid,$productid);
            }
            if($product == 'invoice'){
                _purchaseinvoice($userid,$productid,$amount);
            }  
            if($product == 'recharge'){
                _purchaserecharge($userid,$amount);
            }  
            _updatepayment($tranid,'success');
            _updatecoupon($couponid, 'success');
        }else{
            _updatepayment($tranid,'success');
            _updatecoupon($couponid, 'success');
        }
    }else{
        echo "<script>";
        echo "window.location.href = 'index'";
        echo "</script>";
    }

?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <title>Payment Success</title>
</head>
<style type="text/css">

    body
    {
        background:#f2f2f2;
    }

    .payment
	{
		border:1px solid #f2f2f2;
		height:280px;
        border-radius:20px;
        background:#fff;
	}
   .payment_header
   {
	   background:green;
	   padding:20px;
       border-radius:20px 20px 0px 0px;
	   
   }
   
   .check
   {
	   margin:0px auto;
	   width:50px;
	   height:50px;
	   border-radius:100%;
	   background:#fff;
	   text-align:center;
   }
   
   .check i
   {
	   vertical-align:middle;
	   line-height:50px;
	   font-size:30px;
   }

    .content 
    {
        text-align:center;
    }

    .content  h1
    {
        font-size:25px;
        padding-top:25px;
    }

    .content a
    {
        width:200px;
        height:35px;
        color:#fff;
        border-radius:30px;
        padding:5px 20px;
        background:green;
        transition:all ease-in-out 0.3s;
    }

    .content a:hover
    {
        text-decoration:none;
        background:#000;
    }
   
</style>
<body>
<div class="container">
   <div class="row">
      <div class="col-md-6 mx-auto mt-5">
         <div class="payment">
            <div class="payment_header">
               <div class="check"><i class="fa fa-check" aria-hidden="true"></i></div>
            </div>
            <div class="content">
               <h1>Payment Success !</h1>
               <p>Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. </p>
               <a href="index">Go to Home</a>
            </div>
         </div>
      </div>
   </div>
</div>
</body>
</html>    
               