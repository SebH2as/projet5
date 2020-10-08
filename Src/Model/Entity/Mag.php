<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Mag
{
    private int $id_mag;
    private int $numberMag;
    private string $publication;
    private string $creation_date;
    private string $topics;
    private string $cover;
    private string $title01;
    private string $title02;
    private string $editorial;
    private int $statusPub;

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
