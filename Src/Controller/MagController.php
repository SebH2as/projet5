<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;


class MagController{
        
    private $magManager;
    private $articleManager;
    private $view;
    private $request;
    private $dataLoader;
    private $files;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
        $this->files = new files();
    }

    public function lastMagazine():void//méthode pour afficher la page d'accueil et récupérer le dernier épisode posté
    {
        $getIdMag = $this->magManager->getLastPublishedMag();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $getIdMag[0]->id_mag);
        $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
        $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);

        $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous'));
        
    }

    public function previousMag()
    {
        $previousMag = $this->magManager->previousMag((int) $this->request->get('idMag'));
        if(!empty($previousMag))
        {
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $previousMag[0]->id_mag);
            $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
            $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);
            $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous'));
            exit();
        }
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
        $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);
        $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous'));
    }

    public function nextMag()
    {
        $nextMag = $this->magManager->nextMag((int) $this->request->get('idMag'));
        if(!empty($nextMag))
        {
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $nextMag[0]->id_mag);
            $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
            $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);
            $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous'));
            exit();
        }
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $next = $this->magManager->nextMag((int) $magazine[0]->idMag);
        $previous = $this->magManager->previousMag((int) $magazine[0]->idMag);
        $this->view->render('front/magazine', 'front/layout', compact('magazine', 'next', 'previous'));
    }

    public function chronics():void//méthode pour afficher la page récapitulatrice de toutes les chroniques publiées
    {

        $this->view->render('front/chronics', 'front/layout');
        
    }

    public function essais():void//méthode pour afficher la page récapitulatrice de toutes les essais publiés
    {

        $this->view->render('front/essais', 'front/layout');
        
    }

    public function fictions():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
    {

        $this->view->render('front/fictions', 'front/layout');
        
    }

    public function article():void//méthode pour afficher la page d'un article
    {

        $this->view->render('front/article', 'front/layout');
        
    }

    public function monCompte():void//méthode pour afficher la page mon compte
    {

        $this->view->render('front/monCompte', 'front/layout');
        
    }

    public function connection():void//méthode pour se connecter au back
    {

        $this->view->render('back/listMag', 'back/layout');
        
    }

    public function listMag():void//méthode pour afficher la page récapitulatrice de tous les magazines créés
    {
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
        $this->view->render('back/listMag', 'back/layout', compact('totalMag', 'allMag','nbByPage', 'offset', 'currentpage', 'totalpages'));
        
    }

    public function newMag():void//méthode pour afficher la page de création d'un nouveau magazine
    {

        $totalMag = $this->magManager->countMag();
        $this->view->render('back/newMag', 'back/layout', compact('totalMag'));
        
    }

    public function createNewMag():void//méthode pour créer un nouveau numéro de magazine
    {
        if(!empty($this->request->post('number')))
        {
            $message = null;
            $this->magManager->createMag((int) $this->request->post('number'));
            $magazineByNumber = $this->magManager->findMagByNumber((int) $this->request->post('number'));
            $data = $this->magManager->findMagByIdWithArticles((int) $magazineByNumber[0] -> id_mag);
            $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message'));
        }
    }

    public function modifyMag()
    {
        $message = null;

        /*$this->dataLoader->addData('magManager', 'idMag', 'modifNumber', 'number', 'Le numéro du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');*/

        $this->dataLoader->addData('magManager', 'idMag', 'modifPubli', 'parution', 'La date de publication du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTopics', 'topics', 'Le thème du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle01', 'title01', 'Le titre 1 du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');
        $this->dataLoader->deleteData('magManager', 'idMag', 'deleteTitle01', 'title01', 'Le titre 1 du magazine a été supprimmé', 'pannelmag', 'findMagByIdWithArticles');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle02', 'title02', 'Le titre 2 du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');
        $this->dataLoader->deleteData('magManager', 'idMag', 'deleteTitle02', 'title02', 'Le titre 2 du magazine a été supprimmé', 'pannelmag', 'findMagByIdWithArticles');


        if($this->request->post('modifEdito') !== null)
        {
            $data = $this->magManager->findMagById((int) $this->request->get('idMag'));
            $message = null;
            $this->view->render('back/editorial', 'back/layout', compact('data', 'message'));
            exit();
        }
        
        $this->files->addFiles('magManager', 'modifCover', 'cover', 'idMag', 'La couverture du magazine a été modifiée', 'pannelmag', 'findMagByIdWithArticles');


        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message'));
        
    }

    public function addEdito()
    {
        
        $this->magManager->modifEdito((int) $this->request->get('idMag'), (string) $this->request->post('contentEdito'));
        $data = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $message = "L'éditorial a été modifié";
        $this->view->render('back/editorial', 'back/layout', compact('data', 'message'));
    }

    public function previewMag()
    {
        $magazine = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/previewMag', 'front/layout', compact('magazine'));
    }

    public function deleteMag()
    {
        $dataToErase = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        if(($dataToErase[0]->cover) !== null)
            {
                unlink("../public/images/".$dataToErase[0]->cover);
            }
        foreach ($dataToErase as $articleImgToErase) {
            if(($articleImgToErase->articleCover) !== null)
            {
                unlink("../public/images/".$articleImgToErase->articleCover);
            }
        }
        $this->magManager->deleteMag((int) $this->request->get('idMag'));
        $this->ListMag();
    }

    public function setOnlineMag()
    {
        $message = 'le magazine a été mis en ligne';
        $this->magManager->setOnlineMag((int) $this->request->get('idMag'));

        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message'));
    }

    public function setSavedMag()
    {
        $message = 'le magazine a été sauvegardé';
        $this->magManager->setSavedMag((int) $this->request->get('idMag'));

        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message'));
    }

    
}