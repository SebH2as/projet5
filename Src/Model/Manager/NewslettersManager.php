<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Newsletter;
use Projet5\Model\Repository\NewslettersRepository;

final class NewslettersManager
{
    private NewslettersRepository $newslettersRepo;

    public function __construct(NewslettersRepository $newslettersRepo)
    {
        $this->newslettersRepo = $newslettersRepo;
    }
    
    public function countAllNewsletters(): ?array
    {
        return $this->newslettersRepo->countAllNewsletters();
    }

    public function showAllNewsletters(int $offset, int $nbByPage): ?array
    {
        return $this->newslettersRepo->showAllNewsletters($offset, $nbByPage);
    }

    public function createNewsletter(): bool
    {
        return $this->newslettersRepo->createNewsletter();
    }

    public function showNewslettersById(int $idNewsletter): ?Newsletter
    {
        return $this->newslettersRepo->findNewslettersById($idNewsletter);
    }

    public function setNewsLetterContentById(int $idNewsletter, string $content): bool
    {
        return $this->newslettersRepo->setNewsLetterContentById($idNewsletter, $content);
    }

    public function deleteNewsletterById(int $idNewsletter): void
    {
        $this->newslettersRepo->deleteNewsletterById($idNewsletter);
    }

    public function setNewsLetterSendById(int $idNewsletter, int $sendValue): bool
    {
        return $this->newslettersRepo->setNewsLetterSendById($idNewsletter, $sendValue);
    }
}
