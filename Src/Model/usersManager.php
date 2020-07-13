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

    public function addUser($pseudo, $email, $password, $key)
    {
        $req = $this->bdd->prepare('INSERT INTO users SET pseudo = :newpseudo, email = :newemail, p_w = :newpassword, inscription_date = NOW(), confirmkey = :newkey, actived = 0');
        return $req->execute([
            'newpseudo' => (string) $pseudo,
            'newemail' => (string) $email,
            'newkey' => (int) $key,
            'newpassword' => (string) $password]);
    }

    public function getKeyByPseudo($pseudo)
    {
        $req = $this->bdd->prepare('SELECT confirmkey FROM users WHERE pseudo = :newpseudo');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    public function activeCount($pseudo)
    {
        $req = $this->bdd->prepare('UPDATE users SET actived = 1 WHERE pseudo = :newpseudo ');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
    }
    

    public function pseudoUser($pseudo)
    {
        $req = $this->bdd->prepare('SELECT COUNT(pseudo) FROM users WHERE pseudo = :newpseudo');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetch();
    }

    public function emailUser($email)
    {
        $req = $this->bdd->prepare('SELECT COUNT(pseudo) FROM users WHERE email = :newemail');
        $req->execute([
            'newemail' => (string) $email]);
        return $req->fetch();
    }
}