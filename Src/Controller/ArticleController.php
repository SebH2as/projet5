<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;
use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;

class ArticleController{
        
    private $magManager;
    private $articleManager;
    private $view;
    private $request;
    private $dataLoader;
    private $files;
    private $auth;
    private $noCsrf;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
        $this->files = new files();
        $this->auth = new auth();
        $this->noCsrf = new noCsrf();
    }

    public function addContent()
    {
        $this->auth->requireRole('1');
        $message = 'Le contenu de l\'article a été modifié';
        $this->articleManager->modifContent((int) $this->request->get('idText'), (string) $this->request->post('content'));
        $data = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('data', 'message'));
    }


    public function deleteArticle()
    {
        $this->auth->requireRole('1');
        $message = null;
        $dataToErase = $this->articleManager->findArticleById((int) $this->request->get('idText'));
                    if(($dataToErase[0]->articleCover) !== null)
                    {
                        unlink("../public/images/".$dataToErase[0]->articleCover);
                    }
        $this->articleManager->deleteArticle((int) $this->request->get('idText'));
        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message'));
    }

    public function createNewArticle()
    {
        $this->auth->requireRole('1');
        $message = null;
        $this->articleManager->createArticle((int) $this->request->get('idMag'));
        $articleMostRecent = $this->articleManager->findMostRecentArticle((int) $this->request->post('number'));
        $data = $this->articleManager->findArticleById((int) $articleMostRecent[0] -> id_text);
        $this->view->render('back/pannelArticle', 'back/layout', compact('data', 'message'));
    }
    
    public function modifyArticle()
    {
        $this->auth->requireRole('1');
        $message = null;

        $this->dataLoader->addData('articleManager', 'idText', 'modifRubric', 'rubric', 'La rubrique a été modifiée', 'pannelarticle', 'findArticleById');

        $this->dataLoader->addData('articleManager', 'idText', 'modifTitle', 'title', 'Le titre a été modifiée', 'pannelarticle', 'findArticleById');

        $this->dataLoader->addData('articleManager', 'idText', 'modifAuthor', 'author', "L'auteur a été modifiée", 'pannelarticle', 'findArticleById');

        $this->files->addFiles('articleManager', 'modifCover', 'articleCover', 'idText', "L'image a été modifiée", 'pannelarticle', 'findArticleById');

        $data = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('data', 'message'));
        
    }

    public function previewArticle()
    {
        $this->auth->requireRole('1');
        $article = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/previewArticle', 'front/layout', compact('article'));
    }

    public function chroniques():void//méthode pour afficher la page récapitulatrice de toutes les chroniques publiées
    {
        $user = $this->auth->user();
        $totalChroniques = $this->articleManager->countPublishedChroniques();
        $nbByPage = 6;
        $totalpages = (int) ceil($totalChroniques[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $articles = $this->articleManager->listAllPublishedChroniques((int) $offset, (int) $nbByPage);
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/chroniques', 'front/layout', compact('user', 'magazine', 'articles','totalChroniques', 'nbByPage','currentpage', 'offset', 'totalpages'));
    }

    public function essais():void//méthode pour afficher la page récapitulatrice de toutes les essais publiés
    {
        $user = $this->auth->user();
        $totalEssais = $this->articleManager->countPublishedEssais();
        $nbByPage = 6;
        $totalpages = (int) ceil($totalEssais[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $articles = $this->articleManager->listAllPublishedEssais((int) $offset, (int) $nbByPage);
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/essais', 'front/layout', compact('user', 'magazine', 'articles','totalEssais', 'nbByPage','currentpage', 'offset', 'totalpages'));
    }

    public function fictions():void//méthode pour afficher la page récapitulatrice de toutes les fictions publiées
    {
        $user = $this->auth->user();
        $totalFictions = $this->articleManager->countPublishedFictions();
        $nbByPage = 6;
        $totalpages = (int) ceil($totalFictions[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $articles = $this->articleManager->listAllPublishedFictions((int) $offset, (int) $nbByPage);
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/fictions', 'front/layout', compact('user', 'magazine', 'articles','totalFictions', 'nbByPage','currentpage', 'offset', 'totalpages'));
    }

    public function article():void//méthode pour afficher la page d'un article
    {
        $user = $this->auth->user();
        $currentpage = 1;
        $article = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/article', 'front/layout', compact('magazine', 'article', 'currentpage', 'user'));
        
    }

    public function setMain()
    {
        $this->auth->requireRole('1');
        $message = 'L\'article a été passé à la une';
        $this->articleManager->unsetMain((int) $this->request->get('idMag'));
        $this->articleManager->setMain((int) $this->request->get('idText'));
        $data = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('data', 'message'));
    }

    public function unsetMain()
    {
        $this->auth->requireRole('1');
        $message = 'L\'article a été retiré de la une';
        $this->articleManager->unsetMain((int) $this->request->get('idMag'));
        $data = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('data', 'message'));
    }
}