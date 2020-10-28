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

    public function postLetter(int $idUser, string $author, string $content):bool
    {
        $req = $this->bdd->prepare('INSERT INTO letters SET id_user = :iduser, author = :newauthor, content = :newcontent, post_date = NOW(), published = 0');
        return $req->execute([
            'iduser' => (int) $idUser,
            'newauthor' => (string) $author,
            'newcontent' => (string) $content]);
    }

    public function countUnpubLetters(string $author):array
    {
        $req = $this->bdd->prepare('SELECT COUNT(author) FROM letters WHERE author = :user AND published = 0');
        $req->execute([
            'user' => (string) $author]);
        return $req->fetch();
    }

    public function countPubLetters(string $author):array
    {
        $req = $this->bdd->prepare('SELECT COUNT(author) FROM letters WHERE author = :user AND published = 1');
        $req->execute([
            'user' => (string) $author]);
        return $req->fetch();
    }

    public function getAllLetters(int $offset, int $nbByPage):array
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

    public function countLettersByRelatedMag($numberMag):array // requete pour compter le nombre de lettres total par magazine publié
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM letters WHERE magRelated = :numberMag AND published = 1');
        $req->execute([
            'numberMag' => (string) $numberMag]);
        return $req->fetch();
    }

    public function getLetterById(int $idLetter):array // requete pour récupérer une lettre en fonction de son id
    {
        $req = $this->bdd->prepare('SELECT * FROM letters WHERE id_letter = :idletter');
        $req->execute([
            'idletter' => (string) $idLetter]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function addRelatedMag(int $idLetter, int $relatedMag):void
    {
        $req = $this->bdd->prepare('UPDATE letters SET magRelated = :related WHERE id_letter = :idletter');
        $req->execute([
            'idletter' => (int) $idLetter,
            'related' => (int) $relatedMag]);
    }

    public function response(int $idLetter, string $response):void
    {
        $req = $this->bdd->prepare('UPDATE letters SET response = :resp WHERE id_letter = :idletter');
        $req->execute([
            'idletter' => (int) $idLetter,
            'resp' => (string) $response]);
    }

    public function validatedCourrier(int $idLetter):void
    {
        $req = $this->bdd->prepare('UPDATE letters SET published = 1 WHERE id_letter = :idletter');
        $req->execute([
            'idletter' => (int) $idLetter]);
    }

    public function invalidatedCourrier(int $idLetter):void
    {
        $req = $this->bdd->prepare('UPDATE letters SET published = 0 WHERE id_letter = :idletter');
        $req->execute([
            'idletter' => (int) $idLetter]);
    }

    public function deleteCourrier(int $idLetter):void
    {
        $req = $this->bdd->prepare('DELETE FROM letters WHERE id_letter = :idletter');
        $req->execute([
            'idletter' => (int) $idLetter]);
    }

    public function getCourrierByRelatedMag(int $offset, int $nbByPage, int $numberMag):array
    {
        $req = $this->bdd->prepare('SELECT * FROM letters WHERE magRelated = :numberMag AND published = 1 LIMIT :offset, :limitation ');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->bindValue(':numberMag', $numberMag);
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }
}
