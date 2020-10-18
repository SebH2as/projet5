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

    public function showByIdAndPub(int $idMag): ?Mag
    {
        return $this->magRepo->findByIdAndPub($idMag);
    }

    public function showById(int $idMag): ?Mag
    {
        return $this->magRepo->findById($idMag);
    }

    public function showByNumber(int $numberMag): ?Mag
    {
        return $this->magRepo->findByNumber($numberMag);
    }

    public function countMag(): ?array
    {
        return $this->magRepo->findCountMag();
    }

    public function showAllMag(int $offset, int $nbByPage): ?array
    {
        return $this->magRepo->findAllMag($offset, $nbByPage);
    }

    public function createMag(int $numberMag): bool
    {
        return $this->magRepo->newMag($numberMag);
    }

    public function deleteMagById(int $idMag): void
    {
        $this->magRepo->deleteMagById($idMag);
    }

    public function changeStatusById(int $idMag, int $status): bool
    {
        return $this->magRepo->changeStatusById($idMag, $status);
    }
}
