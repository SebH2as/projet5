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

    public function showByIdmag($idMag): ?array
    {
        return $this->articleRepo->findByIdmag($idMag);
    }

    public function showById($idText): ?Article
    {
        return $this->articleRepo->findById($idText);
    }

    public function countPublishedByType(string $textType): ?array
    {
        return $this->articleRepo->findNumberPubByType($textType);
    }

    public function showAllPublishedByType(string $textType, int $offset, int $nbByPage): ?array
    {
        return $this->articleRepo->findAllPublishedByType($textType, $offset, $nbByPage);
    }

    public function createArticleByIdMag(int $idMag): bool
    {
        return $this->articleRepo->createArticleByIdMag($idMag);
    }

    public function addContent(int $idText, string $content): bool
    {
        return $this->articleRepo->addContent($idText, $content);
    }
}
