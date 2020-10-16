<?php
declare(strict_types=1);

namespace Projet5\Model;

use \PDO ;
use Projet5\Tools\Database;
use Projet5\Tools\Model\User;

class UsersManager
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = Database::getInstance();
        $this->bdd = $this->dataBase->getConnection();
    }

    public function addUser(string $pseudo, string $email, string $password, int $key):bool
    {
        $req = $this->bdd->prepare('INSERT INTO users SET pseudo = :newpseudo, email = :newemail, p_w = :newpassword, inscription_date = NOW(), confirmkey = :newkey, actived = 0');
        return $req->execute([
            'newpseudo' => (string) $pseudo,
            'newemail' => (string) $email,
            'newkey' => (int) $key,
            'newpassword' => (string) $password]);
    }

    public function getUserByPseudo(string $pseudo):object
    {
        $req = $this->bdd->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE pseudo = :newpseudo AND actived = 1');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetchObject(User::class);
    }

    public function getUserById(int $id):object
    {
        $req = $this->bdd->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE id_user = :id AND actived = 1');
        $req->execute([
            'id' => (int) $id]);
        return $req->fetchObject(User::class);
    }

    public function getKeyByPseudo(string $pseudo):array
    {
        $req = $this->bdd->prepare('SELECT confirmkey FROM users WHERE pseudo = :newpseudo');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    public function activeCount(string $pseudo):void
    {
        $req = $this->bdd->prepare('UPDATE users SET actived = 1 WHERE pseudo = :newpseudo ');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
    }
    

    public function pseudoUser(string $pseudo):array
    {
        $req = $this->bdd->prepare('SELECT COUNT(pseudo) FROM users WHERE pseudo = :newpseudo');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetch();
    }

    public function emailUser(string $email):array
    {
        $req = $this->bdd->prepare('SELECT COUNT(email) FROM users WHERE email = :newemail');
        $req->execute([
            'newemail' => (string) $email]);
        return $req->fetch();
    }

    public function newsletter(int $idUser, int $value):void
    {
        $req = $this->bdd->prepare('UPDATE users SET newsletter = :newvalue WHERE id_user = :idUser ');
        $req->execute([
            'newvalue' => (int) $value,
            'idUser' => (int) $idUser]);
    }

    public function modifPass(int $idUser, string $passWordNew):void
    {
        $req = $this->bdd->prepare('UPDATE users SET p_w = :newPass WHERE id_user = :idUser ');
        $req->execute([
            'newPass' => (string) $passWordNew,
            'idUser' => (int) $idUser]);
    }

    public function modifPseudo(int $idUser, string $pseudoNew):void
    {
        $req = $this->bdd->prepare('UPDATE users SET pseudo = :newpseudo WHERE id_user = :idUser ');
        $req->execute([
            'newpseudo' => (string) $pseudoNew,
            'idUser' => (int) $idUser]);
    }

    public function modifEmail(int $idUser, string $emailNew):void
    {
        $req = $this->bdd->prepare('UPDATE users SET email = :newemail WHERE id_user = :idUser ');
        $req->execute([
            'newemail' => (string) $emailNew,
            'idUser' => (int) $idUser]);
    }

    /*public function resetInfos(int $idUser,string $newPseudo,string $newEmail,string $newPass):void
    {
        $req = $this->bdd->prepare('UPDATE users SET pseudo = :newpseudo, email = :newemail, p_w = :newpass WHERE id_user = :idUser ');
        $req->execute([
            'newpseudo' => (string) $newPseudo,
            'newpass' => (string) $newPass,
            'newemail' => (string) $newEmail,
            'idUser' => (int) $idUser]);
    }*/

    public function getAllUsers(int $offset, int $nbByPage):array
    {
        $req = $this->bdd->prepare('SELECT id_user, pseudo, email, role, newsletter, confirmkey, actived, DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS date 
        FROM users 
        LIMIT :offset, :limitation ');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function countUsers():array // requete pour compter le nombre de lettres total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM users');
        $req->execute();
        return $req->fetch();
    }

    public function deleteUserById(int $idUser):void
    {
        $req = $this->bdd->prepare('DELETE FROM users WHERE id_user = :userid');
        $req->execute([
            'userid' => (string) $idUser]);
    }
}
