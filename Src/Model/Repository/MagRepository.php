<?php

declare(strict_types=1);

namespace Projet5\Model\Repository;

use Projet5\Model\Entity\Mag;
use Projet5\Tools\Database;

final class MagRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findByLastAndPub(): ?Mag
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM mag WHERE statusPub = 1 ORDER BY numberMag DESC LIMIT 1; ');
        $req->execute();
        $req->setFetchMode(\PDO::FETCH_CLASS, Mag::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function findByNumber(int $numberMag): ?Mag
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM mag WHERE numberMag = :numMag AND statusPub = 1 ');
        $req->execute(['numMag' => (int) $numberMag]);
        $req->setFetchMode(\PDO::FETCH_CLASS, Mag::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function findByIdAndPub($idMag): ?Mag
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM mag WHERE id_mag = :idMag AND statusPub = 1 ');
        $req->execute(['idMag' => (int) $idMag]);
        $req->setFetchMode(\PDO::FETCH_CLASS, Mag::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function findById(int $idMag): ?Mag
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM mag WHERE id_mag = :idMag ');
        $req->execute(['idMag' => (int) $idMag]);
        $req->setFetchMode(\PDO::FETCH_CLASS, Mag::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function findCountMag(): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) FROM mag');
        $req->execute();
        return $req->fetch();
    }

    public function findAllMag(int $offset, int $nbByPage): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT mag.id_mag,id_text, main, numberMag, publication, editorial, cover, statusPub, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS date,
        COUNT(articles.id_text) AS articlesNb,
        MAX(articles.main) AS articleMain
        FROM mag 
        LEFT JOIN articles ON mag.id_mag = articles.id_mag
        GROUP BY(mag.id_mag)
        ORDER BY numberMag
        LIMIT :offset, :limitation ');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function newMag(int $numberMag): bool
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO mag SET numberMag = :newNumb, creation_date = NOW(), statusPub = 0');
        return $req->execute([
            'newNumb' => (int) $numberMag]);
    }

    public function deleteMagById($idMag): void
    {
        $req = $this->database->getConnection()->prepare('DELETE FROM mag WHERE id_mag = :idMag ');
        $req->execute(['idMag' => $idMag]);
    }

    public function changeStatusById(int $idMag, int $status): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET statusPub = :newStat WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'newStat' => $status]);
    }
}
