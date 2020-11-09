<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Model\Entity\User;
use Projet5\Model\Manager\UsersManager;
use Projet5\Model\Repository\LettersRepository;
use Projet5\Model\Repository\UsersRepository;
use Projet5\Tools\Database;
use Projet5\Tools\Request;
use Projet5\Tools\Session;

class Auth
{
    private UsersManager $usersManager;
    private UsersRepository $usersRepository;
    private LettersRepository $lettersRepository;
    private Request $request;
    private Session $session;
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
        $this->usersRepository = new UsersRepository($this->database);
        $this->lettersRepository = new LettersRepository($this->database);
        $this->request = new Request();
        $this->session = new Session();
        $this->usersManager = new UsersManager($this->usersRepository, $this->lettersRepository, $this->session, $this->request);
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
        if ($user === null) {
            return null;
        }
        if (password_verify($password, $user->getP_w())) {
            $this->session->setSessionData('userId', $user->getId_user());
            return $user;
        }
        return null;
    }

    public function requireRole(int $role): void
    {
        $user = $this->user();
        if ($user === null || $user->getRole() !== $role) {
            header('location: index.php');
            exit();
        }
    }
}
