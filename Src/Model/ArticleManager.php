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

    public function findArticleById(int $idtext):array//requête pour récupérer un numéro de magazine en fonction de son id avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT id_text, id_mag, textType, title, author, content, articleCover, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date FROM articles WHERE id_text = :idText ');
        
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

    public function deleteArticle(int $idText):void//requête pour supprimer un article en fonction de son id
    {
        $req = $this->bdd->prepare('DELETE FROM articles WHERE id_text = :idText ');
        $req->execute(['idText' => $idText]);
    }

    public function modifContent(int $idtext, string $content):bool//requête pour modifier les thématiques d'un magazine
    {
        $req = $this->bdd->prepare('UPDATE articles SET id_text = :sameid, content = :nwContent WHERE id_mag = :sameid ');
        return $req->execute([
            'sameid' => $idtext,
            'nwContent' => $content]);
    }
}