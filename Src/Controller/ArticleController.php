<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;


class ArticleController{
        
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

    public function addContent()
    {
        $message = null;
        $this->articleManager->modifContent((int) $this->request->get('idText'), (string) $this->request->post('content'));
        $magazine = $this->magManager->findMagById((int) $this->request->get('idMag'));
        $article = $this->articleManager->findArticleById((int) $this->request->get('idText'));
        $this->view->render('back/pannelArticle', 'back/layout', compact('magazine','article', 'message'));
    }


    public function deleteArticle()
    {
        $message = null;
        $this->articleManager->deleteArticle((int) $this->request->get('idText'));
        $magazine = $this->magManager->findMagByIdWithArticles((int) $this->request->get('idMag'));
        $this->view->render('back/pannelMag', 'back/layout', compact('magazine', 'message'));
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