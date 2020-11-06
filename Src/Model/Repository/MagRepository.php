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

    public function findCountPubMag(): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) FROM mag WHERE statusPub = 1');
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
        ORDER BY numberMag DESC
        LIMIT :offset, :limitation ');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function findAllPubMag(int $offset, int $nbByPage): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT mag.id_mag,id_text, textType, title01, title02, main, numberMag, publication, editorial, cover, statusPub, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS date,
        COUNT(articles.id_text) AS articlesNb,
        COUNT(IF(articles.textType = "Chronique", 1, NULL))  AS chroniqueNb,
        COUNT(IF(articles.textType = "Essai", 1, NULL))  AS essaiNb,
        COUNT(IF(articles.textType = "Fiction", 1, NULL))  AS fictionNb
        FROM mag 
        LEFT JOIN articles ON mag.id_mag = articles.id_mag
        WHERE statusPub = 1
        GROUP BY(mag.id_mag)
        ORDER BY numberMag DESC
        LIMIT :offset, :limitation ');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function newMag(Mag $mag): bool
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO mag SET numberMag = :newNumb, creation_date = NOW(), statusPub = :statusPub');
        return $req->execute([
            'newNumb' => $mag->getNumberMag(),
            'statusPub' => $mag->getStatusPub()]);
    }

    public function deleteMagById($idMag): void
    {
        $req = $this->database->getConnection()->prepare('DELETE FROM mag WHERE id_mag = :idMag ');
        $req->execute(['idMag' => $idMag]);
    }

    public function updateStatusMag(Mag $mag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET statusPub = :newStat WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $mag->getId_mag(),
            'newStat' => $mag->getStatusPub()]);
    }

    public function modifPublication(Mag $mag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET publication = :newValue WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' =>$mag->getId_mag(),
            'newValue' => $mag->getPublication()]);
    }

    public function modifTitle01(Mag $mag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET title01 = :newValue WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' =>$mag->getId_mag(),
            'newValue' => $mag->getTitle01()]);
    }

    public function deleteTitle01(int $idMag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET title01 = NULL WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag]);
    }
    
    public function modifTitle02(Mag $mag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET title02 = :newValue WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' =>$mag->getId_mag(),
            'newValue' => $mag->getTitle02()]);
    }

    public function deleteTitle02(Mag $idMag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET title02 = NULL WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag]);
    }

    public function modifCover(Mag $mag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET cover = :newValue WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' =>$mag->getId_mag(),
            'newValue' => $mag->getCover()]);
    }

    public function modifEdito(Mag $mag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE mag SET editorial = :newValue WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' =>$mag->getId_mag(),
            'newValue' => $mag->getEditorial()]);
    }

    public function getAllNumberMag():array // requete pour recuperer les numéros de tous les magazines
    {
        $req = $this->database->getConnection()->prepare('SELECT numberMag FROM mag');
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function getPubNumberMag():array // requete pour recuperer les numéros de tous les magazines
    {
        $req = $this->database->getConnection()->prepare('SELECT numberMag FROM mag WHERE statusPub = 1');
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function countNumberMag(int $number): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(numberMag) FROM mag WHERE numberMag = :numberMag');
        $req->execute([
            'numberMag' => (int) $number]);
        return $req->fetch();
    }
}
