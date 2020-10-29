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

    public function countPubMag(): ?array
    {
        return $this->magRepo->findCountPubMag();
    }

    public function showAllMag(int $offset, int $nbByPage): ?array
    {
        return $this->magRepo->findAllMag($offset, $nbByPage);
    }

    public function showAllPubMag(int $offset, int $nbByPage): ?array
    {
        return $this->magRepo->findAllPubMag($offset, $nbByPage);
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

    public function modifPublication(int $idMag, string $value): bool
    {
        return $this->magRepo->modifPublication($idMag, $value);
    }
    
    public function modifTitle01(int $idMag, string $value): bool
    {
        return $this->magRepo->modifTitle01($idMag, $value);
    }

    public function deleteTitle01(int $idMag): bool
    {
        return $this->magRepo->deleteTitle01($idMag);
    }

    public function modifTitle02(int $idMag, string $value): bool
    {
        return $this->magRepo->modifTitle02($idMag, $value);
    }

    public function deleteTitle02(int $idMag): bool
    {
        return $this->magRepo->deleteTitle02($idMag);
    }

    public function modifCover(int $idMag, string $value): bool
    {
        return $this->magRepo->modifCover($idMag, $value);
    }

    public function modifEdito(int $idMag, string $value): bool
    {
        return $this->magRepo->modifEdito($idMag, $value);
    }

    public function showAllNumberMag(): array
    {
        return $this->magRepo->getAllNumberMag();
    }
}
