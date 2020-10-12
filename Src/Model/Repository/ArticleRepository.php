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
        $req = $this->database->getConnection()->prepare('SELECT * FROM articles WHERE id_mag = :idmag');
            $req->execute([
                'idmag' => (int) $id]);
            return $req->fetchALL(\PDO::FETCH_OBJ);

        
    }
}