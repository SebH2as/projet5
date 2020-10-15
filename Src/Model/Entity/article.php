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
    public ?string $date_creation;
    public ?int $main;
}
