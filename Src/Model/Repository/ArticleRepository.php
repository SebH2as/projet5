<?php

declare(strict_types=1);

namespace Projet5\Model\Repository;

use Projet5\Model\Entity\Article;
use Projet5\Tools\Database;

final class ArticleRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findByIdMag(int $id): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM articles WHERE id_mag = :idmag ORDER BY textType, date_creation DESC');
        $req->execute([
                'idmag' => (int) $id]);
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function findById(int $idText): ?Article
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM articles WHERE id_text = :idText');
        $req->execute([
            'idText' => (int) $idText]);
        $req->setFetchMode(\PDO::FETCH_CLASS, Article::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function findNumberPubByType(string $textType): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT COUNT(*) 
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = :textType AND statusPub = 1');
        $req->execute([
            'textType' => (string) $textType]);
        return $req->fetch();
    }

    public function findAllPublishedByType(string $textType, int $offset, int $nbByPage): ?array
    {
        $req = $this->database->getConnection()->prepare('SELECT mag.id_mag AS idMag, numberMag, statusPub, id_text, articles.id_mag AS artIdMag, textType, title, author, teaser, content, articleCover, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date  
        FROM articles 
        LEFT JOIN mag ON mag.id_mag = articles.id_mag
        WHERE textType = :textType AND statusPub = 1
        ORDER BY numberMag DESC
        LIMIT :offset, :limitation');
        $req->bindValue(':limitation', $nbByPage, \PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $req->bindValue(':textType', $textType);
        $req->execute();
        return $req->fetchALL(\PDO::FETCH_OBJ);
    }

    public function createArticleByIdMag(int $idMag): bool
    {
        $req = $this->database->getConnection()->prepare('INSERT INTO articles SET id_mag = :idMag');
        return $req->execute([
            'idMag' => (int) $idMag]);
    }

    public function findMostRecentArticle(): ?Article
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM articles ORDER BY date_creation DESC LIMIT 1');
        $req->execute();
        $req->setFetchMode(\PDO::FETCH_CLASS, Article::class);
        $data = $req->fetch();

        return $data  ? $data : null;
    }

    public function addContent(int $idText, string $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET content = :content WHERE id_text = :idText ');
        return $req->execute([
            'idText' => $idText,
            'content' => $content]);
    }

    public function modifTextType(int $idText, string $textType): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET textType = :textType WHERE id_text = :idText ');
        return $req->execute([
            'idText' => $idText,
            'textType' => $textType]);
    }

    public function modifTitle(int $idText, string $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET title = :content WHERE id_text = :idText ');
        return $req->execute([
            'idText' => $idText,
            'content' => $content]);
    }

    public function modifAuthor(int $idText, string $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET author = :content WHERE id_text = :idText ');
        return $req->execute([
            'idText' => $idText,
            'content' => $content]);
    }

    public function modifTeaser(int $idText, string $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET teaser = :content WHERE id_text = :idText ');
        return $req->execute([
            'idText' => $idText,
            'content' => $content]);
    }

    public function modifCover(int $idText, string $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET articleCover = :content WHERE id_text = :idText ');
        return $req->execute([
            'idText' => $idText,
            'content' => $content]);
    }

    public function deleteArticle(int $idText): void
    {
        $req = $this->database->getConnection()->prepare('DELETE FROM articles WHERE id_text = :idText ');
        $req->execute(['idText' => $idText]);
    }

    public function unsetMainAllArticles(int $idMag): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET main = 0 WHERE id_mag = :idMag ');
        return $req->execute([
            'idMag' => $idMag]);
    }

    public function changeMain(int $idText, int $content): bool
    {
        $req = $this->database->getConnection()->prepare('UPDATE articles SET main = :content WHERE id_text = :idText ');
        return $req->execute([
            'idText' => $idText,
            'content' => $content]);
    }
}
