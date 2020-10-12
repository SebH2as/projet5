<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Article
{
    public int $id_text;
    public int $id_mag;
    public ?string $textType;
    public ?string $title;
    public ?string $author;
    public ?string $content;
    public ?string $teaser;
    public ?string $articleCover;
    public string $date_creation;
    public int $main;

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
