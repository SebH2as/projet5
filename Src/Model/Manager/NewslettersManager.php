<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Newsletter;
use Projet5\Model\Repository\NewslettersRepository;

use Projet5\Tools\Request;
use Projet5\Tools\Session;

final class NewslettersManager
{
    private NewslettersRepository $newslettersRepo;
    private Request $request;
    private Session $session;

    public function __construct(NewslettersRepository $newslettersRepo, Session $session, Request $request)
    {
        $this->newslettersRepo = $newslettersRepo;
        $this->request = $request;
        $this->session = $session;
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

    public function updateContent(int $idNewsletter): bool
    {
        if ($this->request->post('content') === null || empty($this->request->post('content'))
        || empty($this->request->post('save'))) {
            $this->session->setSessionData('error', 'Aucun contenu n\'a été rdigé');
            return false;
        }

        $newsletter = new Newsletter();
        $newsletter->setId_newsletter($idNewsletter);
        $newsletter->setContent((string) $this->request->post('content'));

        $return = $this->newslettersRepo->setNewsLetterContentById($newsletter);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function deleteNewsletterById(int $idNewsletter): bool
    {
        $newsletter = new Newsletter();
        $newsletter->setId_newsletter($idNewsletter);
        
        $this->newslettersRepo->deleteNewsletterById($newsletter);

        return true;
    }
}
