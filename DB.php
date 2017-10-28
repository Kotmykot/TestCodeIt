<?php
class DB
{
    public static function getConnection(){
        $dbn = "mysql:host=localhost;dbname=task;charset=utf8";
        $db = new PDO($dbn,'root','');
        return $db;
    }
}

?>