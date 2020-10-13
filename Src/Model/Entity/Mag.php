<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Mag
{
    public ?int $id_mag;
    public ?int $numberMag;
    public ?string $publication;
    public ?string $creation_date;
    public ?string $topics;
    public ?string $cover;
    public ?string $title01;
    public ?string $title02;
    public ?string $editorial;
    public ?int $statusPub;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }
}
