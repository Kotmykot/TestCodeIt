<?php
session_start();
define('ROOT',dirname(__FILE__));
require_once (ROOT.'/DB.php');

class Account{
    public static function getUser($id,$hash){

        $db = DB::getConnection();
        $db->query("SET NAMES utf8");

        if(!empty($id)) {

            $user = array();
            $result = $db->query("SELECT * FROM users WHERE id = '".$id."' LIMIT 1");
            $i = 0;
            while ($row = $result->fetch()) {
                $user[$i]['id'] = $row['id'];
                $user[$i]['email'] = $row['email'];
                $user[$i]['login'] = $row['login'];
                $user[$i]['user_name'] = $row['user_name'];
                $user[$i]['hash'] = $row['hash'];
                $user[$i]['ip'] = $row['ip'];

                $i++;
            }

        }

        if(($user[0]['hash'] !== $hash)
            or ($user[0]['id'] !== $id)
            or (($user[0]['ip'] !== "INET_ATON(".$_SERVER['REMOTE_ADDR'].")")
                and ($user[0]['ip'] !== "0")))
        {
            setcookie("id", "", time() -3600, "/");
            setcookie("hash", "", time() -3600, "/");
            print "Хм, что-то не получилось";
        }
        else
        {
            return $user;
        }


    }
}


if(isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $user = Account::getUser($_COOKIE['id'],$_COOKIE['hash']);
}else
{
    print "Включите куки";
}

include_once(ROOT.'/logout.php');
?>