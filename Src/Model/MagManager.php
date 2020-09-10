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

    public function findMagByNumber(int $numberMag):array//requête pour récupérer un numéro de magazine en fonction de son numéro avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT id_mag, numberMag, publication, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS date, topics, cover, title01, title02, editorial, statusPub FROM mag WHERE numberMag = :numberMag ');
        
        $req->execute(['numberMag' => (int) $numberMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findMagById(int $idMag):array//requête pour récupérer un numéro de magazine en fonction de son id avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT id_mag, numberMag, publication, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS date, topics, cover, title01, title02, editorial, statusPub FROM mag WHERE id_mag = :idMag ');
        
        $req->execute(['idMag' => (int) $idMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function modifNumber(int $idMag, string $numberMag):bool//requête pour modifier le numéro d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, numberMag = :nwNumber WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwNumber' => $numberMag]);
    }

    public function modifPubli(int $idMag, string $publiMag):bool//requête pour modifier les mois de publication d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, publication = :nwPubli WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwPubli' => $publiMag]);
    }

    public function modifTopics(int $idMag, string $topicsMag):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, topics = :nwTop WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwTop' => $topicsMag]);
    }

    public function modifTitle01(int $idMag, string $title01Mag):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, title01 = :nwTitle01 WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwTitle01' => $title01Mag]);
    }

    public function deleteTitle01(int $idMag):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, title01 = null WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag]);
    }

    public function modifTitle02(int $idMag, string $title02Mag):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, title02 = :nwTitle02 WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwTitle02' => $title02Mag]);
    }

    public function deleteTitle02(int $idMag):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, title02 = null WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag]);
    }

    public function modifEdito(int $idMag, string $editoMag):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, editorial = :nwEdito WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwEdito' => $editoMag]);
    }

    public function modifCover(int $idMag, string $coverMag):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, cover = :nwCover WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'nwCover' => $coverMag]);
    }

    /*public function modifMag(int $idMag,  $column, $content):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE mag SET id = :sameid, COLUMN = :nwContent WHERE id = :sameid ');
        return $req->execute([
            'sameid' => $idMag,
            'columnName' => $column,
            'nwContent' => $content]);
    }*/

    public function countMag():array // requete pour compter le nombre de magazines total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM mag');
        $req->execute();
        return $req->fetch();
    }

    public function countPublishedMag():array // requete pour compter le nombre de magazines publiés total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM mag WHERE statusPub = 1');
        $req->execute();
        return $req->fetch();
    }

    public function previousMag(int $idMag)
    {
        $req = $this->bdd->prepare('SELECT id_mag FROM mag WHERE id_mag < :idMag AND statusPub = 1 ORDER BY creation_date DESC LIMIT 1');
        $req->execute(['idMag' => (int) $idMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function nextMag(int $idMag)
    {
        $req = $this->bdd->prepare('SELECT id_mag FROM mag WHERE id_mag > :idMag AND statusPub = 1 ORDER BY creation_date LIMIT 1');
        $req->execute(['idMag' => (int) $idMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function listAllMag($offset, $nbByPage)
    {
        $req = $this->bdd->prepare('SELECT mag.id_mag,id_text, main, numberMag, publication, editorial, cover, statusPub, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS date,
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
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function getAllNumberPubliMag()
    {
        $req= $req = $this->bdd->prepare('SELECT numberMag FROM mag WHERE statusPub = 1');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function getAllNumberMag()
    {
        $req= $req = $this->bdd->prepare('SELECT numberMag FROM mag');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function getLastPublishedMag()
    {
        $req = $this->bdd->prepare('SELECT id_mag
        FROM mag
        WHERE statusPub = 1
        ORDER BY creation_date DESC ');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findMagByIdWithArticles(int $idMag):array//requête pour récupérer un numéro de magazine en fonction de son id avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT mag.id_mag AS idMag, numberMag, publication, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS dateMag, topics, cover, title01, title02, editorial, statusPub,id_text, textType, content, title, author, articleCover, DATE_FORMAT(date_creation, \'Le %d/%m/%Y\') AS dateArticle, main, teaser,
        COUNT(articles.id_text) AS articlesNb
        FROM mag 
        LEFT JOIN articles ON mag.id_mag = articles.id_mag
        
        WHERE mag.id_mag = :idMag
        GROUP BY(articles.id_text) 
        ORDER BY textType, date_creation');
        
        $req->execute(['idMag' => (int) $idMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findOnlineMagWithArticles(int $idMag):array//requête pour récupérer un numéro de magazine en fonction de son id avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT mag.id_mag AS idMag, numberMag, publication, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS dateMag, topics, cover, title01, title02, editorial, statusPub,articles.id_mag AS articleIdMag, id_text, textType, content, title, author, articleCover, DATE_FORMAT(date_creation, \'Le %d/%m/%Y\') AS dateArticle, main, teaser
        
        FROM mag
        LEFT JOIN articles ON mag.id_mag = articles.id_mag 
        WHERE mag.id_mag = :idMag AND statusPub = 1
        GROUP BY(articles.id_text) 
        ORDER BY textType, date_creation');
        
        $req->execute(['idMag' => (int) $idMag]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function deleteMag(int $idMag):void//requête pour supprimer un magazine en fonction de son id
    {
        $req = $this->bdd->prepare('DELETE FROM mag WHERE id_mag = :idMag ');
        $req->execute(['idMag' => $idMag]);
    }

    public function setOnlineMag(int $idMag)
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, statusPub = 1 WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag]);
    }

    public function setSavedMag(int $idMag)
    {
        $req = $this->bdd->prepare('UPDATE mag SET id_mag = :sameid, statusPub = 0 WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idMag]);
    }
    
}
