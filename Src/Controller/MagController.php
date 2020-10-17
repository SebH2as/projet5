<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\View\View;

final class MagController
{
    private MagManager $magManager;
    private ArticleManager $articleManager;
    private LettersManager $lettersManager;
    private View $view;
    private Request $request;
    private NoCsrf $noCsrf;
    private Auth $auth;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, LettersManager $lettersManager, View $view)
    {
        $this->magManager = $magManager;
        $this->articleManager = $articleManager;
        $this->lettersManager = $lettersManager;
        $this->view = $view;
        $this->request = new Request();
        $this->noCsrf = new noCsrf();
        $this->auth = new auth();
    }

    //index.php?
    public function lastMagazine(): void//méthode pour afficher la page d'accueil et récupérer le dernier magasine publié avec ses articles
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showLastAndPub();
        $articles = $this->articleManager->showByIdmag($magazine->id_mag);

        $previous = $this->magManager->showByNumber($magazine->numberMag - 1);
        $next = $this->magManager->showByNumber($magazine->numberMag + 1);

        $this->view->render(
            [
            'template' => 'front/magazine',
            'data' => [
                'user' => $user,
                'magazine' => $magazine,
                'articles' => $articles,
                'preview' => 0,
                'active' => 1,
                'previous' => $previous,
                'next' => $next,
                ],
            ],
        );
    }


    //index.php?action=magByNumber&idMag=110&numberMag=1
    public function magByNumber(int $idMag): void
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showByNumber((int) $this->request->get('numberMag'));
        if ($magazine) {
            $articles = $this->articleManager->showByIdmag($magazine->id_mag);
            
            $next = $this->magManager->showByNumber($magazine->numberMag + 1);
            $previous = $this->magManager->showByNumber($magazine->numberMag - 1);
            $this->view->render(
                [
                'template' => 'front/magazine',
                'data' => [
                    'user' => $user,
                    'magazine' => $magazine,
                    'articles' => $articles,
                    'preview' => 0,
                    'active' => 1,
                    'previous' => $previous,
                    'next' => $next,
                    ],
                ],
            );
            exit();
        }
        header('location: index.php');
        exit();
    }

    //index.php?action=editorial&idMag=110
    public function editorial(int $idMag):void//méthode pour afficher la page de l'éditorial d'un magazine
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showByIdAndPub($idMag);
        
        $this->view->render(
            [
            'template' => 'front/editorial',
            'data' => [
                'user' => $user,
                'magazine' => $magazine,
                'preview' => 0,
                'active' => 0,
                ],
            ],
        );
    }

    //index.php?action=courrier&idMag=110
    public function courrier(int $idMag):void//méthode pour afficher la page courrier d'un magazine
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showByIdAndPub($idMag);
        
        $totalLetters =  $this->lettersManager->countLettersByRelatedMag($magazine->numberMag);
        $nbByPage = 2;
        $totalpages = (int) ceil($totalLetters[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $letters = $this->lettersManager->showByRelatedMag((int) $offset, (int) $nbByPage, (int) $magazine->numberMag);
        
        $this->view->render(
            [
            'template' => 'front/courrier',
            'data' => [
                'user' => $user,
                'magazine' => $magazine,
                'letters' => $letters,
                'preview' => 0,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                ],
            ],
        );
    }

    //index.php?action=quiSommesNous&idMag=110
    public function quiSommesNous(int $idMag):void//méthode pour afficher la page Qui sommes nous?
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showByIdAndPub($idMag);

        $this->view->render(
            [
            'template' => 'front/quiSommesNous',
            'data' => [
                'user' => $user,
                'magazine' => $magazine,
                'preview' => 0,
                'active' => 0,
                ],
            ],
        );
    }

    //index.php?action=nousRejoindre&idMag=122
    public function nousRejoindre(int $idMag):void//méthode pour afficher la page nous rejoindre
    {
        $error = null;
        $token = $this->noCsrf->createToken();
        $magazine = $this->magManager->showByIdAndPub($idMag);

        $this->view->render(
            [
            'template' => 'front/nousRejoindre',
            'data' => [
                'magazine' => $magazine,
                'error' => $error,
                'preview' => 0,
                'active' => 0,
                'token' => $token,
                ],
            ],
        );
    }

    //index.php?action=connectionPage&idMag=122
    public function connectionPage(int $idMag):void //méthode pour afficher la page de connection
    {
        $error = null;
        if ($this->request->get('error')) {
            $error = 'Pseudo ou mot de passe incorrect';
        }
        
        $token = $this->noCsrf->createToken();
        $magazine = $this->magManager->showByIdAndPub($idMag);
        
        $this->view->render(
            [
            'template' => 'front/connectionPage',
            'data' => [
                'magazine' => $magazine,
                'error' => $error,
                'preview' => 0,
                'active' => 0,
                'token' => $token,
                ],
            ],
        );
    }
}
