<?php

declare(strict_types=1);

namespace Projet5\Model\Repository;

use Projet5\Model\Entity\User;
use Projet5\Tools\Database;

final class UsersRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findUserByPseudo(string $pseudo): ?User
    {
        $req = $this->database->getConnection()->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE pseudo = :newpseudo AND actived = 1');
        $req->execute(['newpseudo' => (string) $pseudo]);
        $req->setFetchMode(\PDO::FETCH_CLASS, User::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function findUserByPseudoNotActived(string $pseudo): ?User
    {
        $req = $this->database->getConnection()->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE pseudo = :newpseudo AND actived = 0');
        $req->execute(['newpseudo' => (string) $pseudo]);
        $req->setFetchMode(\PDO::FETCH_CLASS, User::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function findUserById(int $userId): ?User
    {
        $req = $this->database->getConnection()->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE id_user = :idUser AND actived = 1');
        $req->execute([
            'idUser' => (string) $userId]);
        return $req->fetchObject(User::class);
    }

    public function findAllUserById(int $userId): ?User
    {
        $req = $this->database->getConnection()->prepare('SELECT * , DATE_FORMAT(inscription_date, \'%d/%m/%Y\') AS dateUser FROM users WHERE id_user = :idUser');
        $req->execute([
            'idUser' => (string) $userId]);
        $req->setFetchMode(\PDO::FETCH_CLASS, User::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function setAboNewsletter(User $user): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET newsletter = :newvalue WHERE id_user = :idUser ');
        return $req->execute([
            'newvalue' => $user->getNewsletter(),
            'idUser' => $user->getId_user()]);
    }

    public function unsetAboById(int $idUser, int $value): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET newsletter = :newvalue WHERE id_user = :idUser ');
        return $req->execute([
            'newvalue' => (int) $value,
            'idUser' => (int) $idUser]);
    }

    public function modifPass(User $user): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET p_w = :newValue WHERE id_user = :idUser ');
        return $req->execute([
            'idUser' => $user->getId_user(),
            'newValue' => $user->getP_w()]);
    }

    public function countPseudoUser(string $pseudo): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(pseudo) FROM users WHERE pseudo = :newpseudo');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetch();
    }

    public function modifPseudo(User $user): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET pseudo = :newValue WHERE id_user = :idUser ');
        return $req->execute([
            'idUser' => $user->getId_user(),
            'newValue' => $user->getPseudo()]);
    }

    public function countEmailUser(string $email): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(email) FROM users WHERE email = :newmail');
        $req->execute([
            'newmail' => (string) $email]);
        return $req->fetch();
    }

    public function modifEmail(User $user): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET email = :newValue WHERE id_user = :idUser ');
        return $req->execute([
            'idUser' => $user->getId_user(),
            'newValue' => $user->getEmail()]);
    }

    public function addUser(User $user): bool
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO users SET pseudo = :newpseudo, email = :newemail, p_w = :newpassword, inscription_date = NOW(), confirmkey = :newkey, actived = 0');
        return $req->execute([
            'newpseudo' => $user->getPseudo(),
            'newemail' => $user->getEmail(),
            'newkey' => $user->getConfirmkey(),
            'newpassword' => $user->getP_w()]);
    }

    public function activeAccountByPseudo(User $user): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET actived = :actived WHERE pseudo = :pseudo ');
        return $req->execute([
            'pseudo' => $user->getPseudo(),
        'actived' => $user->getActived()]);
    }

    public function countUsers(): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(id_user) FROM users');
        $req->execute();
        return $req->fetch();
    }

    public function findAllUsers(int $offset, int $nbByPage): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT *
        FROM users 
        ORDER BY inscription_date DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function findAllAboUsers(): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT email FROM users WHERE newsletter = 1 ');
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function deleteUserById(User $user): void
    {
        $req = $this->database->getConnection()->prepare('DELETE FROM users WHERE id_user = :idUser ');
        $req->execute(['idUser' => $user->getId_user()]);
    }
}
