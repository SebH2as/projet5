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

    public function newLetter(Letter $letter): bool
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO letters SET id_user = :user, author = :pseudo, content = :content, magRelated = :numberMag');
        return $req->execute([
            'user' => $letter->getId_user(),
            'pseudo' =>$letter->getAuthor(),
            'content' =>$letter->getContent(),
            'numberMag' =>$letter->getMagRelated()]);
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

    public function findLetterById(int $idLetter): ?Letter
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM letters WHERE id_letter = :idLetter ');
        $req->execute(['idLetter' => (int) $idLetter]);
        $req->setFetchMode(\PDO::FETCH_CLASS, Letter::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function setPublished(Letter $letter): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET published = :content WHERE id_letter = :idLetter ');
        return $req->execute([
            'idLetter' => $letter->getId_letter(),
            'content' => $letter->getPublished()]);
    }

    public function setRelatedMag(Letter $letter): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET magRelated = :numberMag WHERE id_letter = :idLetter ');
        return $req->execute([
            'idLetter' => $letter->getId_letter(),
            'numberMag' => $letter->getMagRelated()]);
    }

    public function setResponseById(Letter $letter): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET response = :content WHERE id_letter = :idLetter ');
        return $req->execute([
            'idLetter' => $letter->getId_letter(),
            'content' => $letter->getResponse()]);
    }

    public function deleteLetterById(Letter $letter): void
    {
        $req = $this->database->getConnection()->prepare('DELETE FROM letters WHERE id_letter = :idLetter ');
        $req->execute(['idLetter' => $letter->getId_letter()]);
    }

    public function changeLetterAuthor(Letter $letter): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE letters SET author = :pseudo WHERE id_User = :idUser ');
        return $req->execute([
            'idUser' => $letter->getId_user(),
            'pseudo' => $letter->getAuthor()]);
    }
}
