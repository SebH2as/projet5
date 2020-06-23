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

        $this->view->render('back/listMag', 'back/layout');
        
    }

    public function newMag():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
    {

        $this->view->render('back/newMag', 'back/layout');
        
    }

    public function createNewMag():void//méthode pour créer un nouveau numéro de magazine
    {
        if(!empty($this->request->post('number')))
        {
            $this->magManager->createMag((int) $this->request->post('number'));
            $magazine = $this->magManager->findMag((int) $this->request->post('number'));
            var_dump($magazine);
            $this->view->render('back/pannelMag', 'back/layout', compact('magazine'));
        }
    }

    public function modifyMag()
    {
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        var_dump($magazine);
        $this->magManager->modifNumberMag((int) $this->request->get('idMag'), (int) $this->request->post('number'));
        
        
    }
}