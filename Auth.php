<?php
session_start();
define('ROOT',dirname(__FILE__));
require_once (ROOT.'/DB.php');
if(!empty($_COOKIE['log'])){
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'Account.php';
    header("Location: http://$host$uri/$extra");
    exit();
}
class Auth{
    // Функция для генерации случайной строки
    private static function generateCode($length=6) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
    }

    public static function getLogin($log, $password)
    {
        unset($_SESSION['log']);
        unset($_SESSION['login_err']);
        $db = DB::getConnection();
        $db->query("SET NAMES utf8");


        if(filter_var($log, FILTER_VALIDATE_EMAIL)) {
            $user = array();
            $result = $db->query("SELECT `id`,`pass` FROM `users` WHERE email='" . $log . "' LIMIT 1");
            $i = 0;
            while ($row = $result->fetch()) {
                $user[$i]['id'] = $row['id'];
                $user[$i]['pass'] = $row['pass'];
                $i++;
            }
        }elseif (preg_match("/^[a-zA-Z0-9]{3,16}$/",$log) or empty($log)){
            $user = array();
            $result = $db->query("SELECT `id`,`pass` FROM `users` WHERE login='" . $log . "' LIMIT 1");
            $i = 0;
            while ($row = $result->fetch()) {
                $user[$i]['id'] = $row['id'];
                $user[$i]['pass'] = $row['pass'];
                $i++;
            }
        }

        if($user[0]['pass'] === md5($password))
        {
            // Генерируем случайное число и шифруем его
            $hash = md5(self::generateCode(10));
            $ip = "INET_ATON(".$_SERVER['REMOTE_ADDR'].")";
            // Записываем в БД новый хеш авторизации и IP
            $db->query("UPDATE users SET hash='".$hash."',ip='".$ip."' WHERE id='".$user[0]['id']."'");
          

            // Ставим куки

            setcookie ("id",$user['id'],time()+3600,"/");
            setcookie("hash", $hash, time()+3600,null,null,null,true); // httponly !!!

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

include_once(ROOT.'/login.php');
?>