<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\Tools\Session;
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
    private Session $session;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, LettersManager $lettersManager, View $view, Request $request, NoCsrf $noCsrf, Auth $auth, Session $session)
    {
        $this->magManager = $magManager;
        $this->articleManager = $articleManager;
        $this->lettersManager = $lettersManager;
        $this->view = $view;
        $this->request = $request;
        $this->noCsrf = $noCsrf;
        $this->auth = $auth;
        $this->session = $session;
    }

    //index.php?
    public function lastMagazine(): void//méthode pour afficher la page d'accueil et récupérer le dernier magasine publié avec ses articles
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showLastAndPub();
        $articles = $this->articleManager->showByIdmag($magazine->getId_mag());

        $previous = $this->magManager->showByNumber($magazine->getNumberMag() - 1);
        $next = $this->magManager->showByNumber($magazine->getNumberMag() + 1);

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
        
        if ($magazine === null) {
            header('location: index.php');
            exit();
        }
        
        $articles = $this->articleManager->showByIdmag($magazine->getId_mag());
        
        $next = $this->magManager->showByNumber($magazine->getNumberMag() + 1);
        $previous = $this->magManager->showByNumber($magazine->getNumberMag() - 1);
        
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

    //index.php?action=listMag
    public function magazines(int $idMag):void//méthode pour afficher la page d'accueil du back récapitulatrice de tous les magazines créés
    {
        $user = $this->auth->user();

        $totalMag = $this->magManager->countPubMag();
        $nbByPage = 5;
        $totalpages = (int) ceil($totalMag[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $magazines = $this->magManager->showAllPubMag((int) $offset, (int) $nbByPage);
        $magazine = $this->magManager->showByIdAndPub($idMag);

        $message = $this->request->get('message');
        
        $this->view->render(
            [
            'template' => 'front/magazines',
            'data' => [
                'magazines' => $magazines,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                'message' => $message,
                'magazine' => $magazine,
                'preview' => 0,
                'active' => 5,
                ],
            ],
        );
    }

    //index.php?action=editorial&idMag=110
    public function editorial(int $idMag):void//méthode pour afficher la page de l'éditorial d'un magazine
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showByIdAndPub($idMag);

        if ($magazine === null) {
            header('location: index.php');
            exit();
        }
        
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

    //index.php?action=quiSommesNous&idMag=110
    public function quiSommesNous(int $idMag):void//méthode pour afficher la page Qui sommes nous?
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showByIdAndPub($idMag);

        if ($magazine === null) {
            header('location: index.php');
            exit();
        }

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

    //index.php?action=listMag
    public function listMag():void//méthode pour afficher la page d'accueil du back récapitulatrice de tous les magazines créés
    {
        $this->auth->requireRole(1);

        $totalMag = $this->magManager->countMag();
        $nbByPage = 5;
        $totalpages = (int) ceil($totalMag[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $allMag = $this->magManager->showAllMag((int) $offset, (int) $nbByPage);

        $message = $this->request->get('message');
        $error = $this->request->get('error');
        
        $this->view->render(
            [
            'template' => 'back/listMag',
            'data' => [
                'allMag' => $allMag,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                'message' => $message,
                'error' => $error,
                ],
            ],
        );
    }

    //index.php?action=newMag
    public function newMag():void//méthode pour afficher la page de création d'un nouveau magazine
    {
        $this->auth->requireRole(1);

        $error = null;
        if ($this->request->get('error') !== null) {
            $error = htmlspecialchars($this->request->get('error'));
        }

        $token = $this->noCsrf->createToken();

        $this->view->render(
            [
            'template' => 'back/newMag',
            'data' => [
                'error' => $error,
                'token' => $token,
                ],
            ],
        );
    }

    //index.php?action=listMag&message=Le%20magazine%20numéro%204%20a%20bien%20été%20créé
    public function createNewMag():void//méthode pour créer un nouveau numéro de magazine
    {
        $this->auth->requireRole(1);
        
        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $error = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=newMag");
            exit();
        }
   
        $newMag = $this->magManager->createMag();

        if (!$newMag) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=newMag&error=$error");
            exit();
        }

        $message = 'Le magazine numéro '. (int) $this->request->post('number') . ' a bien été créé';
        
        header("Location: index.php?action=listMag&message=$message");
        exit();
    }

    //index.php?action=pannelMag&idMag=102
    public function pannelMag(int $idMag):void//méthode pour afficher la page de gestion d'un magazine
    {
        $token = $this->noCsrf->createToken();

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

        $error = null;
        if ($this->request->get('error') !== null) {
            $error = htmlspecialchars($this->request->get('error'));
        }
        
        $magazine = $this->magManager->showById($idMag);
        $articles = $this->articleManager->showByIdmag($idMag);

        if ($magazine === null) {
            $error = 'Le magazine demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $this->view->render(
            [
            'template' => 'back/pannelMag',
            'data' => [
                'magazine' => $magazine,
                'articles' => $articles,
                'token' => $token,
                'message' => $message,
                'error' => $error,
                ],
            ],
        );
    }

    public function confirmDeleteMag(int $idMag):void
    {
        $this->auth->requireRole(1);
        
        $magazine = $this->magManager->showById($idMag);

        if ($magazine === null) {
            $error = 'Le magazine demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }
        
        $this->view->render(
            [
            'template' => 'back/confirmDeleteMag',
            'data' => [
                'magazine' => $magazine,
                ],
            ],
        );
    }

    //index.php?action=listMag&message=Le%20magazine%20numéro%204%20a%20bien%20été%20supprimmé%20avec%20ses%20articles%20et%20images%20associés
    public function deleteMag(int $idMag):void//méthode pour supprimmer un magazine avec ses articles et images associés
    {
        $this->auth->requireRole(1);

        $magToErase = $this->magManager->showById($idMag);

        if ($magToErase === null) {
            $error = 'Le magazine demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $articlesToErase = $this->articleManager->showByIdmag($idMag);

        if (($magToErase->getCover()) !== null && (file_exists("../public/images/".$magToErase->getCover()))) {
            unlink("../public/images/".$magToErase->getCover());
        }
        foreach ($articlesToErase as $articleImgToErase) {
            if (($articleImgToErase->articleCover) !== null && (file_exists("../public/images/".$articleImgToErase->articleCover))) {
                unlink("../public/images/".$articleImgToErase->articleCover);
            }
        }
        $message = 'Le magazine numéro '. $magToErase->getNumberMag() . ' a bien été supprimé avec ses articles et images associés';
        
        $this->magManager->deleteMagById($idMag);

        header("Location: index.php?action=listMag&message=$message");
        exit();
    }

    //index.php?action=pannelMag&idMag=102&message=le%20magazine%20a%20été%20mis%20en%20ligne
    public function changeStatusMag(int $idMag): void
    {
        $this->auth->requireRole(1);
        $message = null;

        $update = $this->magManager->updateStatusMag($idMag);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=pannelMag&idMag=$idMag&error=$error");
            exit();
        }

        $message = $this->session->getSessionData('message');
        $this->session->setSessionData('message', null);

        header("Location: index.php?action=pannelMag&idMag=$idMag&message=$message");
        exit();
    }

    //index.php?action=previewMag&idMag=102
    public function previewMag(int $idMag):void//méthode pour prévisualiser la page d'accueil d'un magazine
    {
        $this->auth->requireRole(1);

        $magazine = $this->magManager->showById($idMag);

        if ($magazine === null) {
            $error = 'Le magazine demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $articles = $this->articleManager->showByIdmag($magazine->getId_mag());

        $this->view->render(
            [
            'template' => 'back/previewMag',
            'data' => [
                'magazine' => $magazine,
                'articles' => $articles,
                'preview' => 1,
                'active' => 1,
                ],
            ],
        );
    }

    public function previewEdito(int $idMag):void
    {
        $this->auth->requireRole(1);
        
        $magazine = $this->magManager->showById($idMag);

        if ($magazine === null) {
            $error = 'Le magazine demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $this->view->render(
            [
            'template' => 'back/previewEdito',
            'data' => [
                'magazine' => $magazine,
                'preview' => 1,
                'active' => 0,
                ],
            ],
        );
    }

    //index.php?action=modifyMag&idMag=102
    public function modifyMag(int $idMag):void//méthode pour modifier les données d'un magazine
    {
        $this->auth->requireRole(1);

        $message = null;
        $error = null;
       
        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=pannelMag&idMag=$idMag&message=$message");
            exit();
        }

        $update = $this->magManager->updateMag($idMag);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=pannelMag&idMag=$idMag&error=$error");
            exit();
        }

        $message = $this->session->getSessionData('message');
        $this->session->setSessionData('message', null);

        header("Location: index.php?action=pannelMag&idMag=$idMag&message=$message");
        exit();
    }

    //index.php?action=editorialBack&idMag=102
    public function editorialBack(int $idMag): void
    {
        $this->auth->requireRole(1);

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

        $error = null;
        if ($this->request->get('error') !== null) {
            $error = htmlspecialchars($this->request->get('error'));
        }

        $token = $this->noCsrf->createToken();
        $magazine = $this->magManager->showById($idMag);

        if ($magazine === null) {
            $error = 'Le magazine demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $this->view->render(
            [
            'template' => 'back/editorial',
            'data' => [
                'magazine' => $magazine,
                'token' => $token,
                'message' => $message,
                'error' => $error,
                ],
            ],
        );
    }

    //index.php?action=addEdito&idMag=102
    public function addEdito(int $idMag):void//méthode pour modifier ou écrire un édito de magazine
    {
        $this->auth->requireRole(1);
        
        $message = null;

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=editorialBack&idMag=$idMag&message=$message");
            exit();
        };

        $update = $this->magManager->updateEdito($idMag);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=editorialBack&idMag=$idMag&error=$error");
            exit();
        }

        $message = $this->session->getSessionData('message');
        $this->session->setSessionData('message', null);

        header("Location: index.php?action=editorialBack&idMag=$idMag&message=$message");
        exit();
    }
}
