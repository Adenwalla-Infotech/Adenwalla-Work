<?php 

_renewalreminder(5);
_removemembership();

function _removemembership(){
    include('../includes/_config.php');
    $date = date('Y-m-d');
    $sql = "SELECT * FROM `tblusers` WHERE `_usermemsleft` = '$date'";
    $query = mysqli_query($conn,$sql);
    if($query){
        $count = mysqli_num_rows($query);
        if($count > 0){
            foreach($query as $data){
                $userid[] = $data['_id'];
            }
            foreach($userid as $id){
                $sql = "UPDATE `tblusers` SET `_usermembership`=null,`_usermemstart`=null,`_usermemsleft`=null WHERE `_id` = '$id'";
                $query = mysqli_query($conn,$sql);
                if($query){
                    $sql = "SELECT * FROM `tblemailtemplates`";
                    $query = mysqli_query($conn,$sql);
                    foreach($query as $data){
                        $template = $data['_canceltemplate'];
                    }
                    $variables = array();
                    $variables['name'] = _getsingleuser($id, '_username');
                    $variables['email'] = _getsingleuser($id, '_useremail');
                    $variables['phone'] = _getsingleuser($id, '_userphone');
                    $sendmail = _usetemplate($template,$variables);
                    _notifyuser(_getsingleuser($id, '_useremail'),_getsingleuser($id, '_userphone'),$sendmail,'Your Subscription has Expired, Kindly Check you mail for more details','Your Subscription is Expired');
                }
            }
        }else{
            echo "No User to be Updated";
        }
    }
}

function _renewalreminder($time){
    include('../includes/_config.php');
    include('../includes/_functions.php');
    $date = strtotime(date('Y-m-d'));
    $duration = date("Y-m-d", strtotime("+$time days", $date));
    $sql = "SELECT * FROM `tblusers` WHERE `_usermemsleft` = '$duration'";
    echo $sql;
    $query = mysqli_query($conn,$sql);
    if($query){
        $count = mysqli_num_rows($query);
        if($count > 0){
            foreach($query as $data){
                $useremail[] = $data['_useremail'];
            }
            $sql = "SELECT * FROM `tblemailtemplates`";
            $query = mysqli_query($conn,$sql);
            foreach($query as $data){
                $template = $data['_remindertemplate'];
            }
            foreach($useremail as $user){
                $sql = "SELECT * FROM `tblusers` WHERE `_useremail` = '$user'";
                $query = mysqli_query($conn,$sql);
                foreach($query as $data){
                    $username = $data['_username'];
                }
                $variables = array();
                $variables['name'] = $username;
                $variables['email'] = $user;
                $variables['time'] = $time;
                $sendmail = _usetemplate($template,$variables);
                _notifyuser($user,'',$sendmail,'','Your Subscription is Expiring');
            }
        }
    }else{
        echo "No User to be Updated";
    }
}

?>