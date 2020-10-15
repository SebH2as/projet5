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

    public function showByRelatedMag(int $offset, int $nbByPage, int $numberMag): ?array
    {
        return $this->lettersRepo->findByRelatedMag($offset, $nbByPage, $numberMag);
    }
}
