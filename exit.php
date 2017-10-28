<?php
session_start();
session_destroy();
setcookie ("log",$_COOKIE['log'],time()-3600,"/");

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'Auth.php';
header("Location: http://$host$uri/$extra");
exit();
?>