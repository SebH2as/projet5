<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Newsletter
{
    public int $id_newsletter;
    public ?string $content;
    public ?string $redaction_date;
    public ?int $send;
}
