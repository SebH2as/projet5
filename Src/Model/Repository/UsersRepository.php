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

    public function setAboById(int $idUser, int $value): void
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET newsletter = :newvalue WHERE id_user = :idUser ');
        $req->execute([
            'newvalue' => (int) $value,
            'idUser' => (int) $idUser]);
    }

    public function unsetAboById(int $idUser, int $value): void
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET newsletter = :newvalue WHERE id_user = :idUser ');
        $req->execute([
            'newvalue' => (int) $value,
            'idUser' => (int) $idUser]);
    }

    public function modifPass(int $idUser, string $value): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET p_w = :newValue WHERE id_user = :idUser ');
        return $req->execute([
            'idUser' => $idUser,
            'newValue' => $value]);
    }

    public function countPseudoUser(string $pseudo): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(pseudo) FROM users WHERE pseudo = :newpseudo');
        $req->execute([
            'newpseudo' => (string) $pseudo]);
        return $req->fetch();
    }

    public function modifPseudo(int $idUser, string $value): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET pseudo = :newValue WHERE id_user = :idUser ');
        return $req->execute([
            'idUser' => $idUser,
            'newValue' => $value]);
    }

    public function countEmailUser(string $email): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(email) FROM users WHERE email = :newmail');
        $req->execute([
            'newmail' => (string) $email]);
        return $req->fetch();
    }

    public function modifEmail(int $idUser, string $value): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET email = :newValue WHERE id_user = :idUser ');
        return $req->execute([
            'idUser' => $idUser,
            'newValue' => $value]);
    }

    public function addUser(string $pseudo, string $email, string $pass, int $key): bool
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO users SET pseudo = :newpseudo, email = :newemail, p_w = :newpassword, inscription_date = NOW(), confirmkey = :newkey, actived = 0');
        return $req->execute([
            'newpseudo' => (string) $pseudo,
            'newemail' => (string) $email,
            'newkey' => (int) $key,
            'newpassword' => (string) $pass]);
    }

    public function activeAccountByPseudo(string $pseudo): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE users SET actived = 1 WHERE pseudo = :pseudo ');
        return $req->execute([
            'pseudo' => (string) $pseudo]);
    }
}
