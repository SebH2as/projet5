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

    public function showByIdAndPub(int $idMag)
    {
        return $this->magRepo->findByIdAndPub($idMag);
    }

    public function showByNumber(int $numberMag)
    {
        return $this->magRepo->findByNumber($numberMag);
    }

}
