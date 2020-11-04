<?php

declare(strict_types=1);

namespace Projet5\Model\Repository;

use Projet5\Model\Entity\Letter;
use Projet5\Tools\Database;

final class LettersRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findNumberByRelatedMag(int $numberMag): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) 
        FROM letters 
        WHERE magRelated = :relatedMag AND published = 1');
        $req->execute([
            'relatedMag' => (int) $numberMag]);
        return $req->fetch();
    }

    public function findNumberUnpubById(int $idUser): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) 
        FROM letters 
        WHERE id_user = :idUser AND published = 0');
        $req->execute([
            'idUser' => (int) $idUser]);
        return $req->fetch();
    }

    public function findNumberPubById(int $idUser): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) 
        FROM letters 
        WHERE id_user = :idUser AND published = 1');
        $req->execute([
            'idUser' => (int) $idUser]);
        return $req->fetch();
    }

    public function findByRelatedMag(int $offset, int $nbByPage, int $numberMag): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT *
        FROM letters 
        WHERE magRelated = :relatedMag AND published = 1
        ORDER BY post_date DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->bindValue(':relatedMag', $numberMag);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function newLetter(int $user, string $pseudo, string $content, int $numberMag)
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO letters SET id_user = :user, author = :pseudo, content = :content, magRelated = :numberMag');
        return $req->execute([
            'user' => (int) $user,
            'pseudo' => (string) $pseudo,
            'content' => (string) $content,
            'numberMag' => (int) $numberMag]);
    }

    public function countAllLetters(): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) FROM letters ');
        $req->execute();
        return $req->fetch();
    }

    public function countPubLetters(): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) FROM letters WHERE published = 1 ');
        $req->execute();
        return $req->fetch();
    }

    public function showAllLetters(int $offset, int $nbByPage): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT *
        FROM letters 
        ORDER BY post_date DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function showPubLetters(int $offset, int $nbByPage): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT *
        FROM letters 
        WHERE published = 1
        ORDER BY post_date DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function findLetterById(int $idLetter): Letter
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM letters WHERE id_letter = :idLetter ');
        $req->execute(['idLetter' => (int) $idLetter]);
        $req->setFetchMode(\PDO::FETCH_CLASS, Letter::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function setLetterPublished(int $idLetter, int $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET published = :content WHERE id_letter = :idLetter ');
        return $req->execute([
            'idLetter' => (int) $idLetter,
            'content' => (int) $content]);
    }

    public function setRelatedMag(int $idLetter, int $numberMag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET magRelated = :numberMag WHERE id_letter = :idLetter ');
        return $req->execute([
            'idLetter' => (int) $idLetter,
            'numberMag' => (int) $numberMag]);
    }

    public function setResponseById(int $idLetter, string  $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET response = :content WHERE id_letter = :idLetter ');
        return $req->execute([
            'idLetter' => (int) $idLetter,
            'content' => (string) $content]);
    }

    public function deleteLetterById(int $idLetter): void
    {
        $req = $this->database->getConnection()->prepare('DELETE FROM letters WHERE id_letter = :idLetter ');
        $req->execute(['idLetter' => $idLetter]);
    }

    public function changeLetterAuthor(int $idUser, string $pseudo): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET author = :pseudo WHERE id_User = :idUser ');
        return $req->execute([
            'idUser' => (int) $idUser,
            'pseudo' => (string) $pseudo]);
    }
}
