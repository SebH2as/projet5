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

    public function setId_letter(int $value): void
    {
        $this->id_letter  = $value;
    }

    public function getId_user(): int
    {
        return $this->id_user;
    }

    public function setId_user(int $value): void
    {
        $this->id_user  = $value;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $value): void
    {
        $this->author  = $value;
    }

    public function getPost_date(): ?string
    {
        return $this->post_date;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $value): void
    {
        $this->content  = $value;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $value): void
    {
        $this->response  = $value;
    }

    public function getPublished(): ?int
    {
        return $this->published;
    }

    public function setPublished(int $value): void
    {
        $this->published  = $value;
    }

    public function getMagRelated(): ?int
    {
        return $this->magRelated;
    }

    public function setMagRelated(int $value): void
    {
        $this->magRelated  = $value;
    }
}
