<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Article;
use Projet5\Model\Repository\ArticleRepository;

final class ArticleManager
{
    private ArticleRepository $articleRepo;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepo = $articleRepository;
    }

    public function showByIdmag($idMag): ?Array
    {
        return $this->articleRepo->findByIdmag($idMag);
    }

    public function showById($idText): ?Article
    {
        return $this->articleRepo->findById($idText);
    }
}
