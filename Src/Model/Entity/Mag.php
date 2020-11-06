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

    public function setId_mag(int $value): void
    {
        $this->id_mag  = $value;
    }

    public function getNumberMag(): int
    {
        return $this->numberMag;
    }

    public function setNumberMag(int $value): void
    {
        $this->numberMag  = $value;
    }

    public function getPublication(): ?string
    {
        return $this->publication;
    }

    public function setPublication(?string $value): void
    {
        $this->publication = $value;
    }

    public function getCreation_date(): ?string
    {
        return $this->publication;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $value): void
    {
        $this->cover = $value;
    }

    public function getTitle01(): ?string
    {
        return $this->title01;
    }

    public function setTitle01(?string $value): void
    {
        $this->title01 = $value;
    }

    public function getTitle02(): ?string
    {
        return $this->title02;
    }

    public function setTitle02(?string $value): void
    {
        $this->title02 = $value;
    }

    public function getEditorial(): ?string
    {
        return $this->editorial;
    }

    public function setEditorial(?string $value): void
    {
        $this->editorial = $value;
    }

    public function getStatusPub(): int
    {
        return $this->statusPub;
    }

    public function setStatusPub(int $value): void
    {
        $this->statusPub = $value;
    }
}
