<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;

class ArticleController{
        
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

    public function addContent()
    {
        $message = null;
        $this->articleManager->modifContent((int) $this->request->get('idText'), (string) $this->request->post('content'));
        $data = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('data', 'message'));
    }


    public function deleteArticle()
    {
        $message = null;
        $this->articleManager->deleteArticle((int) $this->request->get('idText'));
        $data = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('data', 'message'));
    }

    public function createNewArticle()
    {
        $message = null;
        $this->articleManager->createArticle((int) $this->request->get('idMag'));
        $articleMostRecent = $this->articleManager->findMostRecentArticle((int) $this->request->post('number'));
        $data = $this->articleManager->findArticleById((int) $articleMostRecent[0] -> id_text);
        $this->view->render('back/pannelArticle', 'back/layout', compact('data', 'message'));
    }
    
    public function modifyArticle()
    {
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
        $article = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/previewArticle', 'front/layout', compact('article'));
    }
}