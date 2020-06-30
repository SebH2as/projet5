<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;


class MagController{
        
    private $magManager;
    private $articleManager;
    private $view;
    private $request;
    private $dataLoader;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
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
            $magazine = $this->magManager->findMagByIdWithArticles((int) $magazineByNumber[0] -> id_mag);
            $this->view->render('back/pannelMag', 'back/layout', compact('magazine', 'message'));
        }
    }

    public function addData( $manager,  $id,  $method,  $column, $text = null, $template  )
    {
        $message = null;
        if($this->request->post($method) !== null &&  !empty($this->request->post($column)))
        {
            $this->$manager->$method( (int) $this->request->get($id), (string) $this->request->post($column));
            $message = $text;
            $magazine = $this->$manager->findMagByIdWithArticles((int) $this->request->get($id));
            $this->view->render('back/' . $template  , 'back/layout', compact('magazine', 'message'));
            end();
        }
    }

    public function modifyMag()
    {
        $message = null;

        $this->addData('magManager', 'idMag', 'modifNumber', 'number', 'Le numéro du magazine a été modifié', 'pannelmag');

        $this->dataLoader->addData('magManager', 'idMag', 'modifPubli', 'parution', 'La date de publication du magazine a été modifié');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTopics', 'topics', 'Le thème du magazine a été modifié');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle01', 'title01', 'Le titre principal du magazine a été modifié');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle02', 'title02', 'Le titre secondaire du magazine a été modifié');

        if($this->request->post('modifEdito') !== null)
        {
            $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
            $this->view->render('back/editorial', 'back/layout', compact('magazine'));
            exit();
        }
        
        $magazine = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('magazine', 'message'));
        
    }

    public function addEdito()
    {
        $this->magManager->modifEdito((int) $this->request->get('idMag'), (string) $this->request->post('contentEdito'));
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $this->view->render('back/editorial', 'back/layout', compact('magazine'));
    }

    public function previewMag()
    {
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $this->view->render('back/previewMag', 'front/layout', compact('magazine'));
    }

    public function deleteMag()
    {
        $this->magManager->deleteMag((int) $this->request->get('idMag'));
        $allMag = $this->magManager->listAllMag();
        $this->view->render('back/listMag', 'back/layout', compact('allMag'));
    }

    

    
}