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

    public function getUserByPseudoNotActived(string $pseudo): ?User
    {
        return $this->usersRepo->findUserByPseudoNotActived($pseudo);
    }

    public function getUserById(int $userId): ?User
    {
        return $this->usersRepo->findUserById($userId);
    }

    public function getAllUserById(int $userId): ?User
    {
        return $this->usersRepo->findAllUserById($userId);
    }

    public function getAboNewsletter(int $idUser, int $value): bool
    {
        return $this->usersRepo->setAboById($idUser, $value);
    }

    public function abortAboNewsletter(int $idUser, int $value): bool
    {
        return $this->usersRepo->unsetAboById($idUser, $value);
    }

    public function modifPass(int $idUser, string $value): bool
    {
        return $this->usersRepo->modifPass($idUser, $value);
    }

    public function countPseudoUser(string $pseudo): ?array
    {
        return $this->usersRepo->countPseudoUser($pseudo);
    }

    public function modifPseudo(int $idUser, string $value): bool
    {
        return $this->usersRepo->modifPseudo($idUser, $value);
    }

    public function countEmailUser(string $email): ?array
    {
        return $this->usersRepo->countEmailUser($email);
    }

    public function modifEmail(int $idUser, string $value): bool
    {
        return $this->usersRepo->modifEmail($idUser, $value);
    }

    public function addUser(string $pseudo, string $email, string $pass, int $key): bool
    {
        return $this->usersRepo->addUser($pseudo, $email, $pass, $key);
    }

    public function activeAccountByPseudo(string $pseudo): bool
    {
        return $this->usersRepo->activeAccountByPseudo($pseudo);
    }

    public function countUsers(): ?array
    {
        return $this->usersRepo->countUsers();
    }

    public function showAllUsers(int $offset, int $nbByPage): ?array
    {
        return $this->usersRepo->findAllUsers($offset, $nbByPage);
    }

    public function deleteUserById(int $idUser): void
    {
        $this->usersRepo->deleteUserById($idUser);
    }

    public function showAllAboUsers(): void
    {
        $this->usersRepo->findAllAboUsers();
    }
}
