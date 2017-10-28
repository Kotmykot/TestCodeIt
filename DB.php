<?php
class DB
{
    public static function getConnection(){

        try {
        $dbn = "mysql:host=localhost;dbname=task;charset=utf8";
        $db = new PDO($dbn,'root','');
        return $db;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

?>