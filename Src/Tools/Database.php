<?php
declare(strict_types=1);

namespace Projet5\Tools;

use \PDO ;

class Database
{
    private static $instance = null;
    
    private $dbName = 'magbddFinal';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbHost = 'localhost';
    
    private $bdd;

    public function __construct()
    {
        $bdd = new PDO("mysql:host=$this->dbHost; dbname=$this->dbName;charset=utf8", $this->dbUser, $this->dbPass);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->bdd = $bdd;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
    
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->bdd;
    }
}
