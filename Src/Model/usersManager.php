<?php
declare(strict_types=1);

namespace Projet5\Model;

use \PDO ;
use Projet5\Tools\Database;

class UsersManager
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = Database::getInstance();
        $this->bdd = $this->dataBase->getConnection();
    }

    public function addUser($pseudo, $email, $password)
    {
        $req = $this->bdd->prepare('INSERT INTO users SET pseudo = :newpseudo, email = :newemail, p_w = :newpassword, inscription_date = NOW()');
        return $req->execute([
            'newpseudo' => (string) $pseudo,
            'newemail' => (string) $email,
            'newpassword' => (string) $password]);
    }
}