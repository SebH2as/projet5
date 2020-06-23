<?php
declare(strict_types=1);

namespace Projet5\Model;

use \PDO ;
use Projet5\Tools\Database;

class MagManager
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = Database::getInstance();
        $this->bdd = $this->dataBase->getConnection();
    }

    public function createMag(int $newNumber):bool//requête pour créer un nouveau numéro de magazine dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO mag SET numberMag = :newNumb, creation_date = NOW(), statusPub = 0');
        return $req->execute([
            'newNumb' => (int) $newNumber]);
    }

    public function findMag(int $numberMag):array//requête pour récupérer un numéro de magazine en fonction de son numéro avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT id, numberMag, publication, creation_date, topics, cover, title01, title02, editorial, statusPub FROM mag WHERE numberMag = :numberMag ');
        
        $req->execute(['numberMag' => (int) $numberMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findMagById(int $idMag):array//requête pour récupérer un numéro de magazine en fonction de son id avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT id, numberMag, publication, creation_date, topics, cover, title01, title02, editorial, statusPub FROM mag WHERE id = :idMag ');
        
        $req->execute(['idMag' => (int) $idMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function modifNumberMag(int $idMag, int $numberMag):bool//requête pour modifier le numéro d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id = :sameid, numberMag = :nwNumber WHERE id = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwNumber' => $numberMag]);
    }
}