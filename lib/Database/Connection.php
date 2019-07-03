<?php

//namespace App\Models\DAO;

define ("HOST","localhost");
define ("BANCO","db_racer");
define ("USER","root");
define ("PASS","");

abstract class Connection{

    private static $db;

    public static function getConecta()
    {
        try {
            if (self::$db == null)
            {
                self::$db = new \PDO('mysql:host='.HOST.';dbname='.BANCO.';',USER,PASS);
                self::$db->exec("SET CHARACTER SET uft8");
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);   
            }
        } catch (PDOException $erros) {
            die("
                Codigo do erro: #{$erros->getCode()}<br>
                Mensagem: #{$erros->getMessage()}<br>
                Arquivo: #{$erros->getFile()}<br>
                Linha: #{$erros->getLine()}<br>
            ");
        }
        return self::$db;
    }

    /*public abstract function select();
    public abstract function findById();
    public abstract function create();
    public abstract function update();
    public abstract function delete();*/
}
