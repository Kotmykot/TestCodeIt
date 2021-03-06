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
class User{
    protected static function checkEmail($email){
        $db = DB::getConnection();
        $db->query("SET NAMES utf8");
        $user = array();
        $result = $db->query("SELECT `email` FROM `users` WHERE email='" . $email . "' LIMIT 1");
        $i = 0;
        while ($row = $result->fetch()) {
            $user[$i]['email'] = $row['email'];
            $i++;
        }

        if (!empty($user[0]['email'])){
            return false;
        }else{
            return true;
        }
    }

    protected static function checkLogin($login){
        $db = DB::getConnection();
        $db->query("SET NAMES utf8");
        $user = array();
        $result = $db->query("SELECT `login` FROM `users` WHERE login='" . $login . "' LIMIT 1");
        $i = 0;
        while ($row = $result->fetch()) {
            $user[$i]['login'] = $row['login'];
            $i++;
        }

        if (!empty($user[0]['login'])){
            return false;
        }else{
            return true;
        }
    }

    protected static function vEmail($email){
        unset($_SESSION['email_error']);
        unset($_SESSION['email']);
        if (self::checkEmail($email)){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['email_error'] = "Invalid email format or enter your email";
            }else{
                $_SESSION['email'] = $email;
                return true;
            }
        }else{
            $_SESSION['email_error'] = "such email already exists";
            $_SESSION['email'] = $email;
        }

    }

    protected static function vLogin($login){
        unset($_SESSION['login_error']);
        unset($_SESSION['login']);
        if (self::checkLogin($login)){
            if(!preg_match("/^[a-zA-Z0-9]{3,16}$/",$login) or empty($login)) {
                $_SESSION['login_error'] = "Only letters and numbers at least 3 max. 16 or enter your login";
            }else{
                $_SESSION['login'] = $login;
                return true;
            }
        }else{
            $_SESSION['login_error'] = "such login already exists";
            $_SESSION['login'] = $login;
        }


    }

    protected static function vName($name){
        unset($_SESSION['name_error']);
        unset($_SESSION['name']);
        if(!preg_match("/^[a-zA-Z ]*$/",$name) or empty($name)) {
            $_SESSION['name_error'] = "Only letters and white space allowed or enter your name";
        }else{
            $_SESSION['name'] = $name;
            return true;
        }
    }

    protected static function vPassword($password,$repeated_password){
        unset($_SESSION['pass_error']);
        unset($_SESSION['repeated_pass_error']);
        if(!preg_match('/^[a-zA-Z0-9_-]{5,16}+$/',$password) or empty($password)) {
            $_SESSION['pass_error'] = "Only letters and numbers and \"_\" and \"-\" at least 5 max. 16 or enter your password";
        }elseif($password != $repeated_password){
            $_SESSION['repeated_pass_error'] = "The repeated password does not match the password";
        }else{
            return true;
        }
    }

    protected static function vBirth($birth){
        unset($_SESSION['birth_error']);
        unset($_SESSION['birth']);
        $timestamp = strtotime($birth);
        if($timestamp < time()) {
            if (!preg_match("/^(19|20)[0-9]{2}-[0|1][0-9]-[0-3][0-9]/", $birth) or empty($birth)) {
                $_SESSION['birth_error'] = "Only numbers or too old date or enter your name";
                $_SESSION['birth'] = $birth;
            } else {
                $_SESSION['birth'] = $birth;
                return true;
            }
        }else{
            $_SESSION['birth_error'] = "This date has not yet come";
            $_SESSION['birth'] = $birth;
        }


    }

    protected static function vCountry($country){
        unset($_SESSION['country_error']);
        unset($_SESSION['country']);

        if(is_numeric($country) and !empty($country)) {
            $_SESSION['country'] = $country;
            return true;
        }else{
            $_SESSION['country_error'] = "Select a country";
        }
//        if(!preg_match("/^[a-zA-Z ]*$/",$country) or empty($country)) {
//            $_SESSION['country_error'] = "Select a country";
//        }else{
//            $_SESSION['country'] = $country;
//            return true;
//        }
    }

    protected static function vCheckbox($checkbox){
        unset($_SESSION['checkbox_error']);
        unset($_SESSION['checkbox']);
        if($checkbox == "true"){
            $_SESSION['checkbox'] = $checkbox;
            return true;
        }
    }
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
    public static function validationRegistration($post)
    {

        if (!empty($_POST)){
             self::vEmail($post['email']);
             self::vLogin($post['login']);
             self::vName($post['name']);
             self::vPassword($post['pass'],$post['repeated_pass']);
             self::vBirth($post['birth']);
             self::vCountry($post['country']);

            if (!empty($post['check'])
                and self::vName($post['name'])
                and self::vPassword($post['pass'],$post['repeated_pass'])
                and self::vBirth($post['birth'])
                and self::vCountry($post['country'])
                and self::vCheckbox($post['check'])
                and self::vEmail($post['email'])
                and self::vLogin($post['login']))
            {

                    $hash = md5(self::generateCode(10));
                    $ip = "INET_ATON(".$_SERVER['REMOTE_ADDR'].")";

                    self::addUser($_POST['email'], $_POST['login'], $_POST['name'], $_POST['pass'], $_POST['birth'], $_POST['country'], $hash, $ip);


                    $id = self::getId();



                    setcookie ("id",$id[0]['id'],time()+3600);
                    setcookie("hash", $hash, time()+3600,null,null,null,true); // httponly !!!


                    $host  = $_SERVER['HTTP_HOST'];
                    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'Account.php';
                    header("Location: http://$host$uri/$extra");
                    exit();
            }else{
                $_SESSION['checkbox_error'] = "Checked a checkbox";
            }

        }
    }
    protected static function addUser($email,$login,$name,$pass,$birth,$country,$hash,$ip)
    {
        $db = DB::getConnection();
        $db->query("SET NAMES utf8");
        $db->query("INSERT INTO users SET  email='" . $email . "',
                                          login='" . $login . "',
                                          user_name='" . $name . "',
                                          pass='" . md5($pass) . "',
                                          birth='" . $birth . "',
                                          country='" . $country . "',
                                          hash='" . $hash . "',
                                          ip='" . $ip . "',
                                          unix_timestamp='" . time() . "'
        ");

    }

    public static function getCountries()
    {
        $db = DB::getConnection();
        $db->query("SET NAMES utf8");
        $itemsAdmin = array();
        $result = $db->query("SELECT * FROM countries ORDER BY id DESC");
        $i = 0;
        while ($row = $result->fetch()) {
            $itemsAdmin[$i]['id'] = $row['id'];
            $itemsAdmin[$i]['country'] = $row['country'];
            $i++;
        }
        return $itemsAdmin;

    }
    public static function getId()
    {
        $db = DB::getConnection();
        $db->query("SET NAMES utf8");
        $itemsAdmin = array();
        $result = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT 1;");
        $i = 0;
        while ($row = $result->fetch()) {
            $itemsAdmin[$i]['id'] = $row['id'];
            $i++;
        }
        return $itemsAdmin;

    }
}

User::validationRegistration($_POST);

include_once(ROOT.'/registration.php');
?>