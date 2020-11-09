<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Letter;
use Projet5\Model\Repository\LettersRepository;

use Projet5\Tools\Request;
use Projet5\Tools\Session;

final class LettersManager
{
    private LettersRepository $lettersRepo;
    private Request $request;
    private Session $session;

    public function __construct(LettersRepository $lettersRepository, Session $session, Request $request)
    {
        $this->lettersRepo = $lettersRepository;
        $this->request = $request;
        $this->session = $session;
    }

    public function countLettersByRelatedMag(int $numberMag): ?array
    {
        return $this->lettersRepo->findNumberByRelatedMag($numberMag);
    }

    public function countUnpubById(int $idUser): ?array
    {
        return $this->lettersRepo->findNumberUnpubById($idUser);
    }

    public function countPubById(int $idUser): ?array
    {
        return $this->lettersRepo->findNumberPubById($idUser);
    }

    public function showByRelatedMag(int $offset, int $nbByPage, int $numberMag): ?array
    {
        return $this->lettersRepo->findByRelatedMag($offset, $nbByPage, $numberMag);
    }

    public function createLetter(int $user, string $pseudo): bool
    {
        if ($this->request->post('numberMag') === null || empty($this->request->post('numberMag'))) {
            $this->session->setSessionData('error', 'Veuillez associer un thème à votre courrier');
            return false;
        }

        if ($this->request->post('courrier') === null ||  empty($this->request->post('courrier'))) {
            $this->session->setSessionData('error', 'Aucun courrier n\'a été rédigé');
            return false;
        }

        $letter = new Letter();
        $letter->setId_user($user);
        $letter->setAuthor($pseudo);
        $letter->setContent((string) $this->request->post('courrier'));
        $letter->setMagRelated((int) $this->request->post('numberMag'));
        
        $return =  $this->lettersRepo->newLetter($letter);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function countAllLetters(): ?array
    {
        return $this->lettersRepo->countAllLetters();
    }

    public function countPubLetters(): ?array
    {
        return $this->lettersRepo->countPubLetters();
    }

    public function showAllLetters(int $offset, int $nbByPage): ?array
    {
        return $this->lettersRepo->showAllLetters($offset, $nbByPage);
    }

    public function showPubLetters(int $offset, int $nbByPage): ?array
    {
        return $this->lettersRepo->showPubLetters($offset, $nbByPage);
    }

    public function showLetterById(int $idLetter): ?Letter
    {
        return $this->lettersRepo->findLetterById($idLetter);
    }

    public function updatePublished(int $idLetter): bool
    {
        $letter = $this->lettersRepo->findLetterById($idLetter);

        if ($letter === null) {
            $this->session->setSessionData('error', 'La lettre demandée n\'existe pas');
            return false;
        }

        if ($letter->getPublished() === 0) {
            $this->session->setSessionData('message', 'Le courrier a été publié');

            $letter = new Letter();
            $letter->setId_letter($idLetter);
            $letter->setPublished(1);
            $return = $this->lettersRepo->setPublished($letter);

            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }
    
            return $return;
        }

        if ($letter->getPublished() === 1) {
            $this->session->setSessionData('message', 'Le courrier a été retiré du courrier des lecteurs');

            $letter = new Letter();
            $letter->setId_letter($idLetter);
            $letter->setPublished(0);
            $return = $this->lettersRepo->setPublished($letter);

            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }
    
            return $return;
        }
        
        $this->session->setSessionData('error', 'La lettre demandée n\'existe pas');
        return false;
    }

    public function updateRelatedMag(int $idLetter): bool
    {
        $lettertoUpdate = $this->lettersRepo->findLetterById($idLetter);

        if ($lettertoUpdate === null) {
            $this->session->setSessionData('error', 'La lettre demandée n\'existe pas');
            return false;
        }
        
        if ($this->request->post('numberMag') === null || empty($this->request->post('numberMag'))
        || empty($this->request->post('modifRelatedMag'))) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            return false;
        }
        $letter = new Letter();
        $letter->setId_letter($idLetter);
        $letter->setMagRelated((int) $this->request->post('numberMag'));
        
        $return = $this->lettersRepo->setRelatedMag($letter);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function updateResponse(int $idLetter): bool
    {
        $lettertoUpdate = $this->lettersRepo->findLetterById($idLetter);

        if ($lettertoUpdate === null) {
            $this->session->setSessionData('error', 'La lettre demandée n\'existe pas');
            return false;
        }
        
        if ($this->request->post('contentResponse') === null || empty($this->request->post('contentResponse'))
        || empty($this->request->post('saveResponse'))) {
            $this->session->setSessionData('error', 'Aucune réponse n\'a été rédigée');
            return false;
        }

        $letter = new Letter();
        $letter->setId_letter($idLetter);
        $letter->setResponse((string) $this->request->post('contentResponse'));

        $return = $this->lettersRepo->setResponseById($letter);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function deleteLetterById(int $idLetter): bool
    {
        $letterToDelete = $this->lettersRepo->findLetterById($idLetter);

        if ($letterToDelete === null) {
            $this->session->setSessionData('error', 'La lettre demandée n\'existe pas');
            return false;
        }

        $this->session->setSessionData('message', 'Le courrier de '. $letterToDelete->getAuthor() .' a été supprimé');
        
        $letter = new Letter();
        $letter->setId_letter($idLetter);
        
        $this->lettersRepo->deleteLetterById($letter);

        return true;
    }
}
