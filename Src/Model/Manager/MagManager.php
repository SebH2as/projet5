<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Mag;
use Projet5\Model\Repository\MagRepository;

final class MagManager
{
    private MagRepository $magRepo;

    public function __construct(MagRepository $magRepository)
    {
        $this->magRepo = $magRepository;
    }

    public function showAll(): ?array
    {
        // renvoie tous les posts
        return $this->postRepo->findByAll();
    }

    public function showOne(int $id): ?Post
    {
        return $this->postRepo->findById($id);
    }

    public function showLastAndPub(): ?Mag
    {
        return $this->magRepo->findByLastAndPub();
    }
}
