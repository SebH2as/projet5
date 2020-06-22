<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;



class MagController{
        

    private $view;

    public function __construct()
    {
        $this->view = new View();
      
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

    public function article():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
    {

        $this->view->render('front/article', 'front/layout');
        
    }

    public function monCompte():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
    {

        $this->view->render('front/monCompte', 'front/layout');
        
    }

    public function connection():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
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

    public function pannelMag():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
    {

        $this->view->render('back/pannelMag', 'back/layout');
        
    }
}