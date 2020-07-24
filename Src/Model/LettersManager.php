<?php
declare(strict_types=1);

namespace Projet5\Model;

use \PDO ;
use Projet5\Tools\Database;

class LettersManager
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = Database::getInstance();
        $this->bdd = $this->dataBase->getConnection();
    }

    public function postLetter($author, $content)
    {
        $req = $this->bdd->prepare('INSERT INTO letters SET author = :newauthor, content = :newcontent, post_date = NOW(), published = 0');
        return $req->execute([
            'newauthor' => (string) $author,
            'newcontent' => (string) $content]);
    }

    public function countUnpubLetters($author)
    {
        $req = $this->bdd->prepare('SELECT COUNT(author) FROM letters WHERE author = :user AND published = 0');
        $req->execute([
            'user' => (string) $author]);
        return $req->fetch();
    }

    public function countPubLetters($author)
    {
        $req = $this->bdd->prepare('SELECT COUNT(author) FROM letters WHERE author = :user AND published = 1');
        $req->execute([
            'user' => (string) $author]);
        return $req->fetch();
    }

    public function getAllLetters($offset, $nbByPage)
    {
        $req = $this->bdd->prepare('SELECT * FROM letters LIMIT :offset, :limitation ');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function countLetters():array // requete pour compter le nombre de lettres total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM letters');
        $req->execute();
        return $req->fetch();
    }

    public function getLetterById($idLetter) // requete pour récupérer une lettre en fonction de son id
    {
        $req = $this->bdd->prepare('SELECT * FROM letters WHERE id_letter = :idletter');
        $req->execute([
            'idletter' => (string) $idLetter]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }
}