<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Controller\ArticleController;
use Projet5\Controller\LettersController;
use Projet5\Controller\MagController;
use Projet5\Controller\UsersController;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Model\Manager\NewslettersManager;
use Projet5\Model\Manager\UsersManager;

use Projet5\Model\Repository\ArticleRepository;
use Projet5\Model\Repository\LettersRepository;
use Projet5\Model\Repository\MagRepository;
use Projet5\Model\Repository\NewslettersRepository;
use Projet5\Model\Repository\UsersRepository;

use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\View\View;

final class Router
{
    private Database $database;
    private View $view;
    private Request $request;

    private $actionMag =
    [
        'newMag',
        'createNewMag',
        'pannelMag',
        'listMag',
        'modifyMag',
        'previewMag',
        'addEdito',
        'deleteMag',
        'changeStatusMag',
        'editorialBack',
        'magByNumber',
        'magazine',
        'previewEdito',
        'editorial',
        'quiSommesNous',
    ];

    private $actionArticle =
    [
        'addContent',
        'deleteArticle',
        'createNewArticle',
        'pannelArticle',
        'modifyArticle',
        'previewArticle',
        'chroniques',
        'essais',
        'fictions',
        'article',
        'articles',
        'changeMain'
    ];

    private $actionUser =
    [
        'nousRejoindre',
        'connectionPage',
        'connection',
        'monCompte',
        'userDeco',
        'modifUser',
        'modifDataUser',
        'addUser',
        'activation',
        'activationPage',
        'usersBack',
        'deleteUser',
        'adminProfil',
        'modifAdmin'
    ];

    private $actionLetter =
    [
        'nousEcrire',
        'postLetter',
        'lettersBack',
        'letterBack',
        'courrier',
        'previewLetters',
        'setPublished',
        'relatedMag',
        'courrierDelete',
        'setResponse',
        'newslettersBack',
        'newNewsletter',
        'newsletterBack',
        'addContentNewsletter',
        'deleteNewsletter',
        'sendNewsletter'
    ];

    public function __construct()
    {
        $this->database = new Database();
        $this->view = new View();
        $this->request = new request();
        $this->noCsrf = new noCsrf();
        $this->auth = new auth();
    }

    public function run(): void
    {
        if (($this->request->get('action')) !== null) {
            $key = array_search($this->request->get('action'), $this->actionMag, true);
            $methode = $this->actionMag[$key];
            if ($methode === $this->request->get('action')) {
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo);

                $articleRepo = new ArticleRepository($this->database);
                $articleManager = new ArticleManager($articleRepo);

                $lettersRepo = new LettersRepository($this->database);
                $lettersManager = new LettersManager($lettersRepo);

                $controller = new magController($magManager, $articleManager, $lettersManager, $this->view, $this->request, $this->noCsrf, $this->auth);
                $controller->$methode((int) $this->request->get('idMag'));
                exit();
            }

            $key = array_search($this->request->get('action'), $this->actionArticle, true);
            $methode = $this->actionArticle[$key];
            if ($methode === $this->request->get('action')) {
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo);

                $articleRepo = new ArticleRepository($this->database);
                $articleManager = new ArticleManager($articleRepo);

                $controller = new ArticleController($magManager, $articleManager, $this->view, $this->request, $this->noCsrf, $this->auth);
                $controller->$methode((int) $this->request->get('idMag'));
                exit();
            }

            $key = array_search($this->request->get('action'), $this->actionUser, true);
            $methode = $this->actionUser[$key];
            if ($methode === $this->request->get('action')) {
                $usersRepo = new UsersRepository($this->database);
                $usersManager = new UsersManager($usersRepo);
                
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo);
                
                $lettersRepo = new LettersRepository($this->database);
                $lettersManager = new LettersManager($lettersRepo);
                
                $controller = new UsersController($usersManager, $magManager, $lettersManager, $this->view, $this->request, $this->noCsrf, $this->auth);
                $controller->$methode((int) $this->request->get('idMag'));
                exit();
            }

            $key = array_search($this->request->get('action'), $this->actionLetter, true);
            $methode = $this->actionLetter[$key];
            if ($methode === $this->request->get('action')) {
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo);
                
                $lettersRepo = new LettersRepository($this->database);
                $lettersManager = new LettersManager($lettersRepo);

                $newslettersRepo = new NewslettersRepository($this->database);
                $newslettersManager = new NewslettersManager($newslettersRepo);
                
                $controller = new LettersController($magManager, $lettersManager, $newslettersManager, $this->view, $this->request, $this->noCsrf, $this->auth);
                $controller->$methode((int) $this->request->get('idMag'));
                exit();
            }
        }

        $magRepo = new MagRepository($this->database);
        $magManager = new MagManager($magRepo);

        $articleRepo = new ArticleRepository($this->database);
        $articleManager = new ArticleManager($articleRepo);

        $lettersRepo = new LettersRepository($this->database);
        $lettersManager = new LettersManager($lettersRepo);

        $controller = new magController($magManager, $articleManager, $lettersManager, $this->view, $this->request, $this->noCsrf, $this->auth);
        $controller->lastMagazine();
    }
}
