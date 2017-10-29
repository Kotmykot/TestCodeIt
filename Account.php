<?php
session_start();
define('ROOT',dirname(__FILE__));
require_once (ROOT.'/DB.php');

class Account{

    public static function getUser($log){

        $db = DB::getConnection();
        $db->query("SET NAMES utf8");

        if(filter_var($log, FILTER_VALIDATE_EMAIL)) {

            $user = array();
            $result = $db->query("SELECT * FROM `users` WHERE email='" . $log . "' LIMIT 1");
            $i = 0;
            while ($row = $result->fetch()) {
                $user[$i]['email'] = $row['email'];
                $user[$i]['user_name'] = $row['user_name'];
                $i++;
            }

        } elseif (preg_match("/^[a-zA-Z0-9]{3,16}$/",$log) or empty($log)){
            $user = array();
            $result = $db->query("SELECT * FROM `users` WHERE login='" . $log . "'");
            $i = 0;
            while ($row = $result->fetch()) {
                $user[$i]['email'] = $row['email'];
                $user[$i]['user_name'] = $row['user_name'];
                $i++;
            }
        }
        return $user;
    }
}


if(!empty($_COOKIE['log'])){
    $user = Account::getUser($_COOKIE['log']);
}



include_once(ROOT.'/logout.php');
?>