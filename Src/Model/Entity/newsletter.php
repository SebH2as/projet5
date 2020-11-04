<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Newsletter
{
    private int $id_newsletter;
    private ?string $content;
    private ?string $redaction_date;
    private ?int $send;

    public function getId_newsletter(): int
    {
        return $this->id_newsletter;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getRedaction_date(): ?string
    {
        return $this->redaction_date;
    }

    public function getSend(): int
    {
        return $this->send;
    }
}
