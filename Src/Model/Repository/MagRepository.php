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

    public function findByNumber(int $numberMag)
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
}
