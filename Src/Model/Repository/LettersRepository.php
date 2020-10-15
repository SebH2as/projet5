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
}
