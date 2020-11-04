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

    public function getId_mag(): int
    {
        return $this->id_mag;
    }

    public function getTextType(): ?string
    {
        return $this->textType;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    public function getArticleCover(): ?string
    {
        return $this->articleCover;
    }

    public function getDate_creation(): ?string
    {
        return $this->date_creation;
    }

    public function getMain(): ?int
    {
        return $this->main;
    }

}
