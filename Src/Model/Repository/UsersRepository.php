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
}
