<?php

declare(strict_types=1);

namespace Projet5\Model\Entity;

final class User
{
    private int $id_user;
    private ?string $pseudo;
    private ?string $email;
    private ?string $p_w;
    private ?string $inscription_date;
    private int $confirmkey;
    private int $actived;
    private ?int $newsletter;
    private int $role;

    public function getId_user(): int
    {
        return $this->id_user;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getP_w(): ?string
    {
        return $this->p_w;
    }

    public function getInscription_date(): ?string
    {
        return $this->inscription_date;
    }

    public function getConfirmkey(): ?int
    {
        return $this->confirmkey;
    }

    public function getActived(): ?int
    {
        return $this->actived;
    }

    public function getNewsletter(): ?int
    {
        return $this->newsletter;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }
}
