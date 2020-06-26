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
            $magazine = $this->magManager->findMagById((int) $magazineByNumber[0] -> id_mag);
            $this->view->render('back/pannelMag', 'back/layout', compact('magazine', 'message'));
        }
    }

    public function modifyMag()
    {
        $message = null;
        
        $messageText = 
        [
            'Le numéro du magazine a été modifié',    
            'La date de parution du magazine a été modifiée',
            'La thématique du magazine a été modifié',
            'Le titre principale du magazine a été modifié',
            'Le titre secondaire du magazine a été modifié',
        ];

        $this->dataLoader->addData('magManager', 'idMag', 'modifNumber', 'number', 'Le numéro du magazine a été modifié');

        $this->dataLoader->addData('magManager', 'idMag', 'modifPubli', 'parution', 'flute');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTopics', 'topics');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle01', 'title01');

        $this->dataLoader->addData('magManager', 'idMag', 'modifTitle02', 'title02');

        if($this->request->post('modifEdito') !== null)
        {
            $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
            $this->view->render('back/editorial', 'back/layout', compact('magazine'));
            exit();
        }
        
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
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

    public function createNewArticle()
    {
        $message = null;
        $this->articleManager->createArticle((int) $this->request->get('idMag'));
        $articleMostRecent = $this->articleManager->findMostRecentArticle((int) $this->request->post('number'));
        $article = $this->articleManager->findArticleById((int) $articleMostRecent[0] -> id_text);
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('magazine', 'article', 'message'));
    }
    
    public function modifyArticle()
    {
        $message = null;

        $this->dataLoader->addData('articleManager', 'idText', 'modifRubric', 'rubric');

        $this->dataLoader->addData('articleManager', 'idText', 'modifTitle', 'title');

        $this->dataLoader->addData('articleManager', 'idText', 'modifAuthor', 'author');
        
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $article = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('magazine','article', 'message'));
        
    }
}