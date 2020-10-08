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

    public function findById(int $id): ?Post
    {
        $data = $this->database->executeSqlDB(['id'=>$id]);
        // réfléchir à l'hydratation des entités;
        return $data === null ? $data : new Post($data['id'], $data['title'], $data['text']);
    }

    public function findByAll(): ?array
    {
        // SB ici faire l'hydratation des objets
        $data =  $this->database->executeSqlDB(null);
        
        if ($data === null) {
            return null;
        }
        
        // réfléchir à l'hydratation des entités;
        $posts = [];
        foreach ($data as $post) {
            $posts[] = new Post((int)$post['id'], $post['title'], $post['text']);
        }

        return $posts;
    }

    public function create(Post $post) : bool
    {
        return false;
    }

    public function update(Post $post) : bool
    {
        return false;
    }

    public function delete(Post $post) : bool
    {
        return false;
    }
}
