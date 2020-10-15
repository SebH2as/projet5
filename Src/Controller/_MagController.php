<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\ArticleManager;
use Projet5\Model\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Model\UsersManager;
use Projet5\Tools\Auth;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\Tools\Session;
use Projet5\View\View;

class MagController
{
    private $magManager;
    private $articleManager;
    private $usersManager;
    private $lettersManager;
    private $view;
    private $request;
    private $dataLoader;
    private $files;
    private $session;
    private $auth;
    private $noCsrf;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->usersManager = new usersManager();
        $this->lettersManager = new lettersManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
        $this->files = new files();
        $this->session = new session();
        $this->auth = new auth();
        $this->noCsrf = new noCsrf();
    }

    public function magazine():void//méthode pour afficher la page d'accueil et le magazine demandé
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
        $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);

        $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous', 'user'));
    }

    public function lastMagazine():void//méthode pour afficher la page d'accueil et récupérer le dernier épisode posté
    {
        $user = $this->auth->user();
        $getIdMag = $this->magManager->getLastPublishedMag();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $getIdMag[0]->id_mag);
        $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
        $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);

        $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous', 'user'));
    }

    public function previousMag():void//méthode pour naviguer vers les numéros précédents
    {
        $previousMag = $this->magManager->previousMag((int) $this->request->get('idMag'));
        if (!empty($previousMag)) {
            $user = $this->auth->user();
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $previousMag[0]->id_mag);
            $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
            $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);
            $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous', 'user'));
            exit();
        }
        $this->lastMagazine();
    }

    public function nextMag():void//méthode pour naviguer vers les numéros suivants
    {
        $nextMag = $this->magManager->nextMag((int) $this->request->get('idMag'));
        if (!empty($nextMag)) {
            $user = $this->auth->user();
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $nextMag[0]->id_mag);
            $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
            $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);
            $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous', 'user'));
            exit();
        }
        $user = $this->auth->user();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
        $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);
        $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous', 'user'));
    }

    
    public function listMag():void//méthode pour afficher la page récapitulatrice de tous les magazines créés
    {
        $this->auth->requireRole('1');
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
        
        $allMag = $this->magManager->listAllMag((int) $offset, (int) $nbByPage);
        $this->view->render('back/listMag', 'back/layout', compact('totalMag', 'allMag', 'nbByPage', 'offset', 'currentpage', 'totalpages'));
    }

    public function newMag():void//méthode pour afficher la page de création d'un nouveau magazine
    {
        $this->auth->requireRole('1');
        $totalMag = $this->magManager->countMag();
        $token = $this->noCsrf->createToken();
        $this->view->render('back/newMag', 'back/layout', compact('totalMag', 'token'));
    }

    public function createNewMag():void//méthode pour créer un nouveau numéro de magazine
    {
        $this->auth->requireRole('1');
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf'))) {
            if (!empty($this->request->post('number'))) {
                $message = null;
                $this->magManager->createMag((int) $this->request->post('number'));
                $magazineByNumber = $this->magManager->findMagByNumber((int) $this->request->post('number'));
                $data = $this->magManager->findMagByIdWithArticles((int) $magazineByNumber[0] -> id_mag);
                $token = $this->noCsrf->createToken();
                $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message', 'token'));
            }
        }
    }

    public function pannelMag():void//méthode pour afficher la page de gestion d'un magazine
    {
        $token = $this->noCsrf->createToken();
        $message = null;
        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message', 'token'));
    }


    public function modifyMag():void//méthode pour modifier un numéro de magazine
    {
        $this->auth->requireRole('1');
        $message = null;
        
       
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf'))) {
            $this->dataLoader->addData('magManager', 'idMag', 'modifPubli', 'parution', 'La date de publication du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

            $this->dataLoader->addData('magManager', 'idMag', 'modifTopics', 'topics', 'Le thème du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

            $this->dataLoader->addData('magManager', 'idMag', 'modifTitle01', 'title01', 'Le titre 1 du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');
            $this->dataLoader->deleteData('magManager', 'idMag', 'deleteTitle01', 'title01', 'Le titre 1 du magazine a été supprimmé', 'pannelmag', 'findMagByIdWithArticles');

            $this->dataLoader->addData('magManager', 'idMag', 'modifTitle02', 'title02', 'Le titre 2 du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');
            $this->dataLoader->deleteData('magManager', 'idMag', 'deleteTitle02', 'title02', 'Le titre 2 du magazine a été supprimmé', 'pannelmag', 'findMagByIdWithArticles');

            $this->files->addFiles('magManager', 'modifCover', 'cover', 'idMag', 'La couverture du magazine a été modifiée', 'pannelmag', 'findMagByIdWithArticles');
        }
        
        if ($this->request->post('modifEdito') !== null) {
            $data = $this->magManager->findMagById((int) $this->request->get('idMag'));
            $message = null;
            $token = $this->noCsrf->createToken();
            $this->view->render('back/editorial', 'back/layout', compact('data', 'message', 'token'));
            exit();
        }
        
        
        $token = $this->noCsrf->createToken();
        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message', 'token'));
    }

    public function addEdito():void//méthode pour modifier ou écrire un édito de magazine
    {
        $this->auth->requireRole('1');
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf'))) {
            $this->magManager->modifEdito((int) $this->request->get('idMag'), (string) $this->request->post('contentEdito'));
            $data = $this->magManager->findMagById((int) $this->request->get('idMag'));
            $message = "L'éditorial a été modifié";
            $token = $this->noCsrf->createToken();
            $this->view->render('back/editorial', 'back/layout', compact('data', 'message', 'token'));
            exit();
        }
        $data = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $message = "Une erreur est survenue, veuillez recommencer";
        $token = $this->noCsrf->createToken();
        $this->view->render('back/editorial', 'back/layout', compact('data', 'message', 'token'));
    }

    public function previewMag():void//méthode pour prévisualiser la page d'accueil d'un magazine
    {
        $this->auth->requireRole('1');
        $magazine = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/previewMag', 'front/layout', compact('magazine'));
    }

    public function deleteMag():void//méthode pour supprimmer un magazine avec ses articles et images associés
    {
        $this->auth->requireRole('1');
        $dataToErase = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        if (($dataToErase[0]->cover) !== null) {
            unlink("../public/images/".$dataToErase[0]->cover);
        }
        foreach ($dataToErase as $articleImgToErase) {
            if (($articleImgToErase->articleCover) !== null) {
                unlink("../public/images/".$articleImgToErase->articleCover);
            }
        }
        $this->magManager->deleteMag((int) $this->request->get('idMag'));
        $this->ListMag();
    }

    public function setOnlineMag():void//méthode pour mettre en ligne un numéro de magazine
    {
        $this->auth->requireRole('1');
        $message = 'le magazine a été mis en ligne';
        $this->magManager->setOnlineMag((int) $this->request->get('idMag'));

        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $token = $this->noCsrf->createToken();
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message', 'token'));
    }

    public function setSavedMag():void//méthode pour sauvegarder un numéro de magazine et le retirer des magazines en ligne
    {
        $this->auth->requireRole('1');
        $message = 'le magazine a été sauvegardé';
        $this->magManager->setSavedMag((int) $this->request->get('idMag'));

        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $token = $this->noCsrf->createToken();
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message', 'token'));
    }

    public function readersLetters():void//méthode pour afficher la page courrier d'un magazine
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        
        $totalLetters =  $this->lettersManager->countLettersByRelatedMag($magazine[0]->numberMag);
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
        
        $letters = $this->lettersManager->getCourrierByRelatedMag((int) $offset, (int) $nbByPage, (int) $magazine[0]->numberMag);
        $this->view->render('front/readersLetters', 'front/layout', compact('magazine', 'letters', 'totalLetters', 'totalpages', 'currentpage', 'user'));
    }

    public function previewLetters():void //méthode pour afficher une page preview du courrier depuis le back
    {
        $this->auth->requireRole('1');
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        
        $totalLetters =  $this->lettersManager->countLettersByRelatedMag($magazine[0]->numberMag);
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
        
        $letters = $this->lettersManager->getCourrierByRelatedMag($offset, $nbByPage, $magazine[0]->numberMag);
        $this->view->render('back/previewLetters', 'front/layout', compact('magazine', 'letters', 'totalLetters', 'totalpages', 'currentpage'));
    }

    public function editorial():void//méthode pour afficher la page de l'éditorial
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/editorial', 'front/layout', compact('magazine', 'user'));
    }

    public function previewEdito():void //méthode pour afficher une page preview de l'éditorial depuis le back
    {
        $this->auth->requireRole('1');
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/previewEdito', 'front/layout', compact('magazine'));
    }

    public function whoWeAre():void//méthode pour afficher la page Qui sommes nous?
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/whoWeAre', 'front/layout', compact('magazine', 'user'));
    }
}
