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

    public function setId_user(int $value): void
    {
        $this->id_user  = $value;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $value): void
    {
        $this->pseudo  = $value;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $value): void
    {
        $this->email  = $value;
    }

    public function getP_w(): ?string
    {
        return $this->p_w;
    }

    public function setP_w(string $value): void
    {
        $this->p_w  = $value;
    }

    public function getInscription_date(): ?string
    {
        return $this->inscription_date;
    }

    public function getConfirmkey(): ?int
    {
        return $this->confirmkey;
    }

    public function setConfirmkey(int $value): void
    {
        $this->confirmkey  = $value;
    }

    public function getActived(): ?int
    {
        return $this->actived;
    }

    public function setActived(int $value): void
    {
        $this->actived  = $value;
    }

    public function getNewsletter(): ?int
    {
        return $this->newsletter;
    }

    public function setNewsletter(int $value): void
    {
        $this->newsletter  = $value;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $value): void
    {
        $this->role  = $value;
    }
}
