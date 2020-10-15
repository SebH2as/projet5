<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Letter
{
    public int $id_letter;
    public int $id_user;
    public ?string $author;
    public ?string $post_date;
    public ?string $content;
    public ?string $response;
    public ?string $published;
    public ?string $magRelated;
}
