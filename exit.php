<?php
if(!empty($_COOKIE['log'])){
    session_start();
    session_destroy();
    setcookie ("id",$user['id'],time()-3600,"/");
    setcookie("hash", $hash, time()-3600,null,null,null,true); // httponly !!!
}


$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'Auth.php';
header("Location: http://$host$uri/$extra");
exit();
?>