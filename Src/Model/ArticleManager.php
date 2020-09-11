<?php
declare(strict_types=1);

namespace Projet5\Model;

use \PDO ;
use Projet5\Tools\Database;

class ArticleManager
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = Database::getInstance();
        $this->bdd = $this->dataBase->getConnection();
    }

    public function createArticle(int $idMag):bool//requête pour créer un nouvel artile associé à un magazine dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO articles SET id_mag = :newIdMAg, date_creation = NOW()');
        return $req->execute([
            'newIdMAg' => (int) $idMag]);
    }

    public function findMostRecentArticle()
    {
        $req = $this->bdd->prepare('SELECT id_text FROM articles ORDER BY date_creation DESC ');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findArticleById(int $idtext):array//requête pour récupérer un article en fonction de son id avec le numéro de magazine auquel il est associé
    {
        $req = $this->bdd->prepare('SELECT numberMag, id_text, articles.id_mag AS ArtIdMag, textType, title, author, content, teaser, main, articleCover, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date 
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE id_text = :idText ');
        
        $req->execute(['idText' => (int) $idtext]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function modifRubric(int $idText, string $newType)
    {
        $req = $this->bdd->prepare('UPDATE articles SET id_text = :sameid, textType = :nwType WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idText,
            'nwType' => $newType]);
    }

    public function modifTitle(int $idText, string $newTitle)
    {
        $req = $this->bdd->prepare('UPDATE articles SET id_text = :sameid, title = :nwTitle WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idText,
            'nwTitle' => $newTitle]);
    }

    public function modifAuthor(int $idText, string $newAuthor)
    {
        $req = $this->bdd->prepare('UPDATE articles SET id_text = :sameid, author = :nwAuthor WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idText,
            'nwAuthor' => $newAuthor]);
    }

    public function modifCover(int $idText, string $coverArticle):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE articles SET id_text = :sameid, articleCover = :nwCover WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idText,
            'nwCover' => $coverArticle]);
    }

    public function deleteArticle(int $idText):void//requête pour supprimer un article en fonction de son id
    {
        $req = $this->bdd->prepare('DELETE FROM articles WHERE id_text = :idText ');
        $req->execute(['idText' => $idText]);
    }

    public function modifContent(int $idtext, string $content):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE articles SET id_text = :sameid, content = :nwContent WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idtext,
            'nwContent' => $content]);
    }

    public function modifTeaser(int $idtext, string $teaser):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE articles SET id_text = :sameid, teaser = :nwTeaser WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idtext,
            'nwTeaser' => $teaser]);
    }

    public function ListAllPublishedFictions($offset, $nbByPage)
    {
        $req = $this->bdd->prepare('SELECT mag.id_mag AS idMag, numberMag, statusPub, id_text, articles.id_mag AS artIdMag, textType, title, author, teaser, content, articleCover, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date  
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = "Fiction" AND statusPub = 1
        ORDER BY numberMag DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function countPublishedFictions():array // requete pour compter le nombre d'épisodes total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) 
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = "Fiction" AND statusPub = 1');
        $req->execute();
        return $req->fetch();
    }

    public function ListAllPublishedChroniques($offset, $nbByPage)
    {
        $req = $this->bdd->prepare('SELECT mag.id_mag AS idMag, numberMag, statusPub, id_text, articles.id_mag AS artIdMag, textType, title, author, teaser, content, articleCover, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date  
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = "Chronique" AND statusPub = 1
        ORDER BY numberMag DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function countPublishedChroniques():array // requete pour compter le nombre d'épisodes total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) 
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = "Chronique" AND statusPub = 1');
        $req->execute();
        return $req->fetch();
    }

    public function ListAllPublishedEssais($offset, $nbByPage)
    {
        $req = $this->bdd->prepare('SELECT mag.id_mag AS idMag, numberMag, statusPub, id_text, articles.id_mag AS artIdMag, textType, title, author, teaser, content, articleCover, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date  
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = "Essai" AND statusPub = 1
        ORDER BY numberMag DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function countPublishedEssais():array // requete pour compter le nombre d'épisodes total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) 
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = "Essai" AND statusPub = 1');
        $req->execute();
        return $req->fetch();
    }

    public function unsetMain($idMag)
    {
        $req = $this->bdd->prepare('UPDATE articles SET main = 0 WHERE id_mag = :idmag ');
        return $req->execute([
            'idmag' => $idMag]);
    }

    public function setMain($idText)
    {
        $req = $this->bdd->prepare('UPDATE articles SET main = 1 WHERE id_text = :idtext ');
        return $req->execute([
            'idtext' => $idText]);
    }
}