<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Article
{
    private int $id_text;
    private int $id_mag;
    private ?string $textType;
    private ?string $title;
    private ?string $author;
    private ?string $content;
    private ?string $teaser;
    private ?string $articleCover;
    private ?string $date_creation;
    private ?int $main;


    public function getId_text(): int
    {
        return $this->id_text;
    }

    public function setId_text(int $value): void
    {
        $this->id_text  = $value;
    }

    public function getId_mag(): int
    {
        return $this->id_mag;
    }

    public function setId_mag(int $value): void
    {
        $this->id_mag  = $value;
    }

    public function getTextType(): ?string
    {
        return $this->textType;
    }

    public function setTextType(string $value): void
    {
        $this->textType  = $value;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $value): void
    {
        $this->title  = $value;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $value): void
    {
        $this->author  = $value;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $value): void
    {
        $this->content  = $value;
    }

    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    public function setTeaser(string $value): void
    {
        $this->teaser  = $value;
    }

    public function getArticleCover(): ?string
    {
        return $this->articleCover;
    }

    public function setArticleCover(string $value): void
    {
        $this->articleCover  = $value;
    }

    public function getDate_creation(): ?string
    {
        return $this->date_creation;
    }

    public function getMain(): ?int
    {
        return $this->main;
    }

    public function setMain(int $value): void
    {
        $this->main  = $value;
    }
}
