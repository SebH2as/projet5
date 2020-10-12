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
        $req = $this->database->getConnection()->prepare('SELECT * FROM post WHERE id = :idpost');
            $req->execute([
                'idpost' => (int) $id]);
            $req->setFetchMode(\PDO::FETCH_CLASS, Post::class);
            return $data = $req->fetch();

        return $data === null ? $data : new Post($data['id'], $data['title'], $data['text']);
    }

    public function findByAll(): ?array
    {
        $req = $this->database->getConnection()->query('SELECT * FROM post');
        return $req->fetchALL(\PDO::FETCH_CLASS, Post::class);
    }

    public function findByLastAndPub(): ?Mag
    {
        $req = $this->database->getConnection()->prepare('SELECT * FROM mag WHERE statusPub = 1 ORDER BY creation_date DESC LIMIT 1; ');
            $req->execute();
            $req->setFetchMode(\PDO::FETCH_CLASS, Mag::class);
            return $data = $req->fetch();

        return $data === null ? $data : new Mag($data['id_mag'], $data['numberMag'], $data['publication'], $data['creation_date'], $data['topics'], $data['cover'], $data['title01'], $data['title02'], $data['editorial'], $data['statusPub']);
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
