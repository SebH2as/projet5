<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class User
{
    public int $id_user;
    public ?string $pseudo;
    public ?string $email;
    public ?string $p_w;
    public ?string $inscription_date;
    public int $confirmkey;
    public int $actived;
    public ?string $newsletter;
    public int $role;
}
