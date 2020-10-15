<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Model\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Model\UsersManager;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;
use Projet5\Tools\Request;
use Projet5\Tools\Session;
use Projet5\View\View;

class Auth
{
    private $magManager;
    private $articleManager;
    private $usersManager;
    private $view;
    private $request;
    private $dataLoader;
    private $files;
    private $session;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->usersManager = new usersManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
        $this->files = new files();
        $this->session = new session();
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
