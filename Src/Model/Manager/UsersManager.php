<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\User;
use Projet5\Model\Repository\UsersRepository;

final class UsersManager
{
    private UsersRepository $usersRepo;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepo = $usersRepository;
    }

    public function getUserByPseudo(string $pseudo): ?User
    {
        return $this->usersRepo->findUserByPseudo($pseudo);
    }

    public function getUserById(int $userId): ?User
    {
        return $this->usersRepo->findUserById($userId);
    }
}
