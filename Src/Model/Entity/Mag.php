<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Mag
{
    private int $id_mag;
    private int $numberMag;
    private ?string $publication;
    private ?string $creation_date;
    private ?string $topics;
    private ?string $cover;
    private ?string $title01;
    private ?string $title02;
    private ?string $editorial;
    private int $statusPub;

    public function getId_mag(): int
    {
        return $this->id_mag;
    }

    public function getNumberMag(): int
    {
        return $this->numberMag;
    }

    public function getPublication(): ?string
    {
        return $this->publication;
    }

    public function getCreation_date(): ?string
    {
        return $this->publication;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function getTitle01(): ?string
    {
        return $this->title01;
    }

    public function getTitle02(): ?string
    {
        return $this->title02;
    }

    public function getEditorial(): ?string
    {
        return $this->editorial;
    }

    public function getStatusPub(): int
    {
        return $this->statusPub;
    }
}
