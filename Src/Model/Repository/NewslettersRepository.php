<?php

declare(strict_types=1);

namespace Projet5\Model\Repository;

use Projet5\Model\Entity\Newsletter;
use Projet5\Tools\Database;

final class NewslettersRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function countAllNewsletters(): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) FROM newsletters ');
        $req->execute();
        return $req->fetch();
    }

    public function showAllNewsletters(int $offset, int $nbByPage): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT *
        FROM newsletters 
        ORDER BY redaction_date DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function createNewsletter(): bool
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO newsletters SET redaction_date = NOW()');
        return $req->execute();
    }

    public function findNewslettersById(int $idNewsletter): ?Newsletter
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM newsletters WHERE id_newsletter = :idNewsletter ');
        $req->execute(['idNewsletter' => (int) $idNewsletter]);
        $req->setFetchMode(\PDO::FETCH_CLASS, Newsletter::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function setNewsLetterContentById(int $idNewsletter, string $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE newsletters SET content = :content WHERE id_newsletter = :idNewsletter ');
        return $req->execute([
            'idNewsletter' => (int) $idNewsletter,
            'content' => (string) $content]);
    }

    public function deleteNewsletterById(int $idNewsletter): void
    {
        $req = $this->database->getConnection()->prepare('DELETE FROM newsletters WHERE id_newsletter = :idNewsletter ');
        $req->execute(['idNewsletter' => $idNewsletter]);
    }

    public function setNewsLetterSendById(int $idNewsletter, int $sendValue): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE newsletters SET send = :sendValue WHERE id_newsletter = :idNewsletter ');
        return $req->execute([
            'idNewsletter' => (int) $idNewsletter,
            'sendValue' => (int) $sendValue]);
    }
}
