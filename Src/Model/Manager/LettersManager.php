<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Letter;
use Projet5\Model\Repository\LettersRepository;

final class LettersManager
{
    private LettersRepository $lettersRepo;

    public function __construct(LettersRepository $lettersRepository)
    {
        $this->lettersRepo = $lettersRepository;
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

    public function createLetter(int $user, string $pseudo, string $content): bool
    {
        return $this->lettersRepo->newLetter($user, $pseudo, $content);
    }

    public function countAllLetters(): ?array
    {
        return $this->lettersRepo->countAllLetters();
    }

    public function showAllLetters(int $offset, int $nbByPage): ?array
    {
        return $this->lettersRepo->showAllLetters($offset, $nbByPage);
    }

    public function showLetterById(int $idLetter): Letter
    {
        return $this->lettersRepo->findLetterById($idLetter);
    }

    public function setLetterPublished(int $idLetter, int $content): bool
    {
        return $this->lettersRepo->setLetterPublished($idLetter, $content);
    }

    public function setRelatedMag(int $idLetter, int  $numberMag): bool
    {
        return $this->lettersRepo->setRelatedMag($idLetter, $numberMag);
    }

    public function setResponseById(int $idLetter, string  $content): bool
    {
        return $this->lettersRepo->setResponseById($idLetter, $content);
    }

    public function deleteLetterById(int $idLetter): void
    {
        $this->lettersRepo->deleteLetterById($idLetter);
    }
}
