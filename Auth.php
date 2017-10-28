<?php
session_start();
define('ROOT',dirname(__FILE__));
require_once (ROOT.'\DB.php');
if(!empty($_COOKIE['log'])){
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'Account.php';
    header("Location: http://$host$uri/$extra");
    exit();
}
class Auth{
    public static function getLogin($log, $password)
    {
        unset($_SESSION['log']);
        unset($_SESSION['login_err']);
        $db = DB::getConnection();
        $db->query("SET NAMES utf8");


        if(filter_var($log, FILTER_VALIDATE_EMAIL)) {
            $user = array();
            $result = $db->query("SELECT `id` FROM `users` WHERE email='" . $log . "' AND pass='" . md5($password) . "' LIMIT 1");
            $i = 0;
            while ($row = $result->fetch()) {
                $user[$i]['id'] = $row['id'];
                $i++;
            }
        }elseif (preg_match("/^[a-zA-Z0-9]{3,16}$/",$log) or empty($log)){
            $user = array();
            $result = $db->query("SELECT `id` FROM `users` WHERE login='" . $log . "' AND pass='" . md5($password) . "' LIMIT 1");
            $i = 0;
            while ($row = $result->fetch()) {
                $user[$i]['id'] = $row['id'];
                $i++;
            }
        }

        if (!empty($user[0]['id'])){
            setcookie ("log",$log,time()+3600,"/");

            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'Account.php';
            header("Location: http://$host$uri/$extra");
            exit();
        }else{
            $_SESSION['login_err'] = "Login or password entered incorrectly or empty";
            $_SESSION['log'] = $log;
        }
    }
}
if (!empty($_POST)) {
    Auth::getLogin($_POST['log'], $_POST['pass']);
}

include_once(ROOT.'\login.php');
?>