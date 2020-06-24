<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Model\MagManager;
use Projet5\Tools\Request;



class MagController{
        
    private $magManager;
    private $view;
    private $request;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->request = new request();
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

    public function listMag():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
    {
        $allMag = $this->magManager->listAllMag();
        $this->view->render('back/listMag', 'back/layout', compact('allMag'));
        
    }

    public function newMag():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
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
            $magazine = $this->magManager->findMagById((int) $magazineByNumber[0] -> id);
            $this->view->render('back/pannelMag', 'back/layout', compact('magazine', 'message'));
        }
    }

    public function modifyMag()
    {
        $message = null;

        if($this->request->post('modifNumber') !== null &&  !empty($this->request->post('number')))
        {
            $this->magManager->modifNumberMag((int) $this->request->get('idMag'), (int) $this->request->post('number'));
            $message = 'Le numéro du magazine a été modifié';
        }

        if($this->request->post('modifPubli') !== null &&  !empty($this->request->post('parution')))
        {
            $this->magManager->modifPubliMag((int) $this->request->get('idMag'), (string) $this->request->post('parution'));
            $message = 'La date de parution du magazine a été modifiée';
        }

        if($this->request->post('modifTopics') !== null &&  !empty($this->request->post('thematic')))
        {
            $this->magManager->modifTopicsMag((int) $this->request->get('idMag'), (string) $this->request->post('thematic'));
            $message = 'La thématique du magazine a été modifié';
        }

        if($this->request->post('modifTitle01') !== null &&  !empty($this->request->post('title01')))
        {
            $this->magManager->modifTitle01Mag((int) $this->request->get('idMag'), (string) $this->request->post('title01'));
            $message = 'Le titre principale du magazine a été modifié';
        }

        if($this->request->post('modifTitle02') !== null &&  !empty($this->request->post('title02')))
        {
            $this->magManager->modifTitle02Mag((int) $this->request->get('idMag'), (string) $this->request->post('title02'));
            $message = 'Le titre secondaire du magazine a été modifié';
        }

        if($this->request->post('modifEdito') !== null)
        {
            $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
            $this->view->render('back/editorial', 'back/layout', compact('magazine'));
            return;
        }
        
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('magazine', 'message'));
        
    }

    public function addEdito()
    {
        
        $this->magManager->modifEditoMag((int) $this->request->get('idMag'), (string) $this->request->post('contentEdito'));
    }

    public function previewMag()
    {
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $this->view->render('back/previewMag', 'front/layout', compact('magazine'));
    }
}