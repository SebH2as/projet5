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
        $req = $this->bdd->prepare('INSERT INTO textmag SET id_mag = :newIdMAg, creation_date = NOW()');
        return $req->execute([
            'newIdMAg' => (int) $idMag]);
    }

    public function findMostRecentArticle()
    {
        $req = $this->bdd->prepare('SELECT id_text FROM textmag ORDER BY creation_date DESC ');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findArticleById(int $idtext):array//requête pour récupérer un numéro de magazine en fonction de son id avec ses articles associés
    {
        $req = $this->bdd->prepare('SELECT id_text, id_mag, textType, title, author, content, articleCover, DATE_FORMAT(creation_date, \'%d/%m/%Y\') AS date FROM textmag WHERE id_text = :idText ');
        
        $req->execute(['idText' => (int) $idtext]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function modifRubric(int $idText, string $newType)
    {
        $req = $this->bdd->prepare('UPDATE textmag SET id_text = :sameid, textType = :nwType WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idText,
            'nwType' => $newType]);
    }

    public function modifTitle(int $idText, string $newTitle)
    {
        $req = $this->bdd->prepare('UPDATE textmag SET id_text = :sameid, title = :nwTitle WHERE id_text = :sameid ');
        return $req->execute([
            'sameid' => $idText,
            'nwTitle' => $newTitle]);
    }
}