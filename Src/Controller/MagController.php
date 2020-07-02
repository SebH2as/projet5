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

    public function magazine():void//méthode pour afficher la page d'accueil et récupérer le dernier épisode posté
    {

        $this->view->render('front/magazine', 'front/layout');
        
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
        $allMag = $this->magManager->listAllMag();
        $this->view->render('back/listMag', 'back/layout', compact('allMag'));
        
    }

    public function newMag():void//méthode pour afficher la page de création d'un nouveau magazine
    {

        $this->view->render('back/newMag', 'back/layout');
        
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

        $this->dataLoader->addData('magManager', 'idMag', 'modifNumber', 'number', 'Le numéro du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

        $this->dataLoader->addData('magManager', 'idMag', 'modifPubli', 'parution', 'La date de publication du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTopics', 'topics', 'Le thème du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle01', 'title01', 'Le titre principal du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle02', 'title02', 'Le titre secondaire du magazine a été modifié', 'pannelmag', 'findMagByIdWithArticles');

        if($this->request->post('modifEdito') !== null)
        {
            $data = $this->magManager->findMagById((int) $this->request->get('idMag'));
            $this->view->render('back/editorial', 'back/layout', compact('data'));
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
        $this->view->render('back/editorial', 'back/layout', compact('data'));
    }

    public function previewMag()
    {
        $magazine = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/previewMag', 'front/layout', compact('magazine'));
    }

    public function deleteMag()
    {
        $this->magManager->deleteMag((int) $this->request->get('idMag'));
        $allMag = $this->magManager->listAllMag();
        $this->view->render('back/listMag', 'back/layout', compact('allMag'));
    }

    

    
}