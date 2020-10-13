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

    public function showLastAndPub(): ?Mag
    {
        return $this->magRepo->findByLastAndPub();
    }

    public function showPreviousMag(int $idMag): ?Mag
    {
        return $this->magRepo->findPreviousMag($idMag);
    }

    public function showNextMag(int $idMag): ?Mag
    {
        return $this->magRepo->findNextMag($idMag);
    }

    public function showByIdAndPub(int $idMag): ?Mag
    {
        return $this->magRepo->findByIdAndPub($idMag);
    }
}
