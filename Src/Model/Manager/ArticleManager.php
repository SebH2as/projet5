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

    public function showMostRecentArticle(): ?Article
    {
        return $this->articleRepo->findMostRecentArticle();
    }

    public function addContent(int $idText, string $content): bool
    {
        return $this->articleRepo->addContent($idText, $content);
    }

    public function modifTextType(int $idText, string $textType): bool
    {
        return $this->articleRepo->modifTextType($idText, $textType);
    }

    public function modifTitle(int $idText, string $content): bool
    {
        return $this->articleRepo->modifTitle($idText, $content);
    }

    public function modifAuthor(int $idText, string $content): bool
    {
        return $this->articleRepo->modifAuthor($idText, $content);
    }

    public function modifTeaser(int $idText, string $content): bool
    {
        return $this->articleRepo->modifTeaser($idText, $content);
    }

    public function modifCover(int $idText, string $content): bool
    {
        return $this->articleRepo->modifCover($idText, $content);
    }

    public function deleteArticle(int $idText): void
    {
        $this->articleRepo->deleteArticle($idText);
    }

    public function unsetMainAllArticles(int $idMag): bool
    {
        return $this->articleRepo->unsetMainAllArticles($idMag);
    }

    public function changeMain(int $idText, int $content): bool
    {
        return $this->articleRepo->changeMain($idText, $content);
    }
}
