<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Model\Entity\User;
use Projet5\Model\Manager\UsersManager;
use Projet5\Model\Repository\UsersRepository;
use Projet5\Tools\Database;
use Projet5\Tools\Request;
use Projet5\Tools\Session;

class Auth
{
    private UsersManager $usersManager;
    private UsersRepository $usersRepository;
    private Request $request;
    private Session $session;
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
        $this->usersRepository = new UsersRepository($this->database);
        $this->usersManager = new UsersManager($this->usersRepository);
        $this->request = new Request();
        $this->session = new Session();
    }

    public function user(): ?user
    {
        $userId = $this->session->getSessionData('userId') ?? null;
        if ($userId === null) {
            return null;
        }
        $user = $this->usersManager->getUserById((int) $userId);
        return $user ?: null;
    }
    
    public function login(string $pseudo, string $password): ?user
    {
        $user = $this->usersManager->getUserByPseudo($pseudo);
        if ($user === false) {
            return null;
        }
        if (password_verify($password, $user->p_w)) {
            $this->session->setSessionData('userId', $user->id_user);
            return $user;
        }
        return null;
    }

    public function requireRole(string $role): void
    {
        $user = $this->user();
        if ($user === null || $user->role !== $role) {
            header('location: index.php');
            exit();
        }
    }
}
