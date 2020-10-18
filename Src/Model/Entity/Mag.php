<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class Mag
{
    public int $id_mag;
    public int $numberMag;
    public ?string $publication = null;
    public ?string $creation_date = null;
    public ?string $topics = null;
    public ?string $cover = null;
    public ?string $title01 = null;
    public ?string $title02 = null;
    public ?string $editorial = null;
    public int $statusPub;
}
