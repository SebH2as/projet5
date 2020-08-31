<?php
declare(strict_types=1);

namespace Projet5\Model;

use \PDO ;
use Projet5\Tools\Database;
use Projet5\Tools\User;

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

    public function getUserByPseudo($pseudo)
    {
        $req = $this->bdd->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE pseudo = :newpseudo AND actived = 1');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetchObject(User::class);
    }

    public function getUserById($id)
    {
        $req = $this->bdd->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE id_user = :id AND actived = 1');
        $req->execute([
            'id' => (string) $id]);
        return $req->fetchObject(User::class);
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
        $req = $this->bdd->prepare('SELECT COUNT(email) FROM users WHERE email = :newemail');
        $req->execute([
            'newemail' => (string) $email]);
        return $req->fetch();
    }

    public function newsletter($idUser, $value)
    {
        $req = $this->bdd->prepare('UPDATE users SET newsletter = :newvalue WHERE id_user = :idUser ');
        $req->execute([
            'newvalue' => (int) $value,
            'idUser' => (string) $idUser]);
    }

    public function modifPass($idUser, $passWordNew)
    {
        $req = $this->bdd->prepare('UPDATE users SET p_w = :newPass WHERE id_user = :idUser ');
        $req->execute([
            'newPass' => (string) $passWordNew,
            'idUser' => (string) $idUser]);
    }

    public function modifPseudo($idUser, $pseudoNew)
    {
        $req = $this->bdd->prepare('UPDATE users SET pseudo = :newpseudo WHERE id_user = :idUser ');
        $req->execute([
            'newpseudo' => (string) $pseudoNew,
            'idUser' => (string) $idUser]);
    }

    public function modifEmail($idUser, $emailNew)
    {
        $req = $this->bdd->prepare('UPDATE users SET email = :newemail WHERE id_user = :idUser ');
        $req->execute([
            'newemail' => (string) $emailNew,
            'idUser' => (string) $idUser]);
    }

    public function resetInfos($idUser, $newPseudo, $newEmail, $newPass)
    {
        $req = $this->bdd->prepare('UPDATE users SET pseudo = :newpseudo, email = :newemail, p_w = :newpass WHERE id_user = :idUser ');
        $req->execute([
            'newpseudo' => (string) $newPseudo,
            'newpass' => (string) $newPass,
            'newemail' => (string) $newEmail,
            'idUser' => (string) $idUser]);
    }
}