<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Letter
{
    private int $id_letter;
    private int $id_user;
    private ?string $author;
    private ?string $post_date;
    private ?string $content;
    private ?string $response;
    private ?int $published;
    private ?int $magRelated;

    public function getId_letter(): int
    {
        return $this->id_letter;
    }

    public function getId_user(): int
    {
        return $this->id_user;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getPost_date(): ?string
    {
        return $this->post_date;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function getPublished(): ?int
    {
        return $this->published;
    }

    public function getMagRelated(): ?int
    {
        return $this->magRelated;
    }
}
