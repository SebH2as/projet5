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
use Projet5\Tools\Session;

use Projet5\View\View;

final class Router
{
    private Database $database;
    private View $view;
    private Request $request;
    private Auth $auth;
    private NoCsrf $noCsrf;
    private Session $session;

    private $actionMag =
    [
        'newMag',
        'createNewMag',
        'pannelMag',
        'listMag',
        'modifyMag',
        'previewMag',
        'addEdito',
        'confirmDeleteMag',
        'deleteMag',
        'changeStatusMag',
        'editorialBack',
        'magByNumber',
        'magazine',
        'magazines',
        'previewEdito',
        'editorial',
        'quiSommesNous',
    ];

    private $actionArticle =
    [
        'addContent',
        'confirmDeleteArticle',
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
        'newsletterAbo',
        'addUser',
        'activation',
        'activationPage',
        'usersBack',
        'deleteUser',
        'adminProfil',
        'modifAdmin',
        'confirmDeleteUser',
        'confirmDeleteUserBack',
        'userDeleteSelf',
        'sendNewsletter'
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
        'confirmDeleteLetter',
        'setResponse',
        'newslettersBack',
        'newNewsletter',
        'newsletterBack',
        'addContentNewsletter',
        'deleteNewsletter',
        
    ];

    public function __construct()
    {
        $this->database = new Database();
        $this->view = new View();
        $this->request = new request();
        $this->noCsrf = new noCsrf();
        $this->auth = new auth();
        $this->session = new session();
    }

    public function run(): void
    {
        if (($this->request->get('action')) !== null) {
            $key = array_search($this->request->get('action'), $this->actionMag, true);
            $methode = $this->actionMag[$key];
            if ($methode === $this->request->get('action')) {
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo, $this->session, $this->request);

                $articleRepo = new ArticleRepository($this->database);
                $articleManager = new ArticleManager($articleRepo, $this->session, $this->request);

                $lettersRepo = new LettersRepository($this->database);
                $lettersManager = new LettersManager($lettersRepo, $this->session, $this->request);

                $controller = new magController($magManager, $articleManager, $lettersManager, $this->view, $this->request, $this->noCsrf, $this->auth, $this->session);
                $controller->$methode((int) $this->request->get('idMag'));
                exit();
            }

            $key = array_search($this->request->get('action'), $this->actionArticle, true);
            $methode = $this->actionArticle[$key];
            if ($methode === $this->request->get('action')) {
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo, $this->session, $this->request);

                $articleRepo = new ArticleRepository($this->database);
                $articleManager = new ArticleManager($articleRepo, $this->session, $this->request);

                $controller = new ArticleController($magManager, $articleManager, $this->view, $this->request, $this->noCsrf, $this->auth, $this->session);
                $controller->$methode((int) $this->request->get('idMag'), (int) $this->request->get('idText'));
                exit();
            }

            $key = array_search($this->request->get('action'), $this->actionUser, true);
            $methode = $this->actionUser[$key];
            if ($methode === $this->request->get('action')) {
                $lettersRepo = new LettersRepository($this->database);
                $lettersManager = new LettersManager($lettersRepo, $this->session, $this->request);
                
                $newslettersRepo = new NewslettersRepository($this->database);
                $newslettersManager = new NewslettersManager($newslettersRepo, $this->session, $this->request);
                
                $usersRepo = new UsersRepository($this->database);
                $usersManager = new UsersManager($usersRepo, $lettersRepo, $newslettersRepo, $this->session, $this->request);
                
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo, $this->session, $this->request);
                
                $controller = new UsersController($usersManager, $magManager, $lettersManager, $newslettersManager, $this->view, $this->request, $this->noCsrf, $this->auth, $this->session);
                $controller->$methode((int) $this->request->get('idMag'));
                exit();
            }

            $key = array_search($this->request->get('action'), $this->actionLetter, true);
            $methode = $this->actionLetter[$key];
            if ($methode === $this->request->get('action')) {
                $magRepo = new MagRepository($this->database);
                $magManager = new MagManager($magRepo, $this->session, $this->request);
                
                $lettersRepo = new LettersRepository($this->database);
                $lettersManager = new LettersManager($lettersRepo, $this->session, $this->request);

                $newslettersRepo = new NewslettersRepository($this->database);
                $newslettersManager = new NewslettersManager($newslettersRepo, $this->session, $this->request);
                
                $controller = new LettersController($magManager, $lettersManager, $newslettersManager, $this->view, $this->request, $this->noCsrf, $this->auth, $this->session);
                $controller->$methode((int) $this->request->get('idMag'));
                exit();
            }
        }

        $magRepo = new MagRepository($this->database);
        $magManager = new MagManager($magRepo, $this->session, $this->request);

        $articleRepo = new ArticleRepository($this->database);
        $articleManager = new ArticleManager($articleRepo, $this->session, $this->request);

        $lettersRepo = new LettersRepository($this->database);
        $lettersManager = new LettersManager($lettersRepo, $this->session, $this->request);

        $controller = new magController($magManager, $articleManager, $lettersManager, $this->view, $this->request, $this->noCsrf, $this->auth, $this->session);
        $controller->lastMagazine();
    }
}
