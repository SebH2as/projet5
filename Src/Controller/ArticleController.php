<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Auth;
use Projet5\Tools\DataLoader;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\View\View;

final class ArticleController
{
    private MagManager $magManager;
    private View $view;
    private ArticleManager $articleManager;
    private Request $request;
    private Auth $auth;
    private NoCsrf $noCsrf;
    private Dataloader $dataLoader;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, View $view)
    {
        $this->magManager = $magManager;
        $this->view = $view;
        $this->articleManager = $articleManager;
        $this->request = new Request();
        $this->auth = new auth();
        $this->noCsrf = new NoCsrf();
        $this->dataLoader = new DataLoader();
    }

    //index.php?action=article&idMag=148&idText=3
    public function article(int $idMag):void//méthode pour afficher la page d'un article
    {
        $user = $this->auth->user();
        $article = $this->articleManager->showById((int) $this->request->get('idText'));
        $magazine = $this->magManager->showByIdAndPub($idMag);

        if ($article === null) {
            header("Location: index.php");
            exit();
        }

        $this->view->render(
            [
            'template' => 'front/article',
            'data' => [
                'user' => $user,
                'magazine' => $magazine,
                'article' => $article,
                'preview' => 0,
                'active' => 0,
                ],
            ],
        );
    }

    //index.php?action=articles&idMag=122&type=2
    public function articles(int $idMag):void//méthode pour afficher la page récapitulatrice de toutes les chroniques publiées
    {
        $type = ['',
                '',
                'Chronique',
                'Essai',
                'Fiction'];

        $textType = (int) $this->request->get('type');
        
        $totalArticles = $this->articleManager->countPublishedByType($type[$textType]);
        $nbByPage = 6;
        $totalpages = (int) ceil($totalArticles[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
        };
        $offset = ($currentpage - 1) * $nbByPage;
        
        $user = $this->auth->user();
        $articles = $this->articleManager->showAllPublishedByType((string) $type[$textType], (int) $offset, (int) $nbByPage);
        $magazine = $this->magManager->showByIdAndPub($idMag);

        $this->view->render(
            [
            'template' => 'front/articles',
            'data' => [
                'user' => $user,
                'magazine' => $magazine,
                'articles' => $articles,
                'preview' => 0,
                'active' => $textType,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                'textType' => $textType,
                'rubrique' => $type[$textType]
                ],
            ],
        );
    }

    //index.php?action=pannelArticle&idMag=102&idText=128
    public function pannelArticle(int $idMag): void
    {
        $this->auth->requireRole(1);

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = $this->request->get('message');
        }

        $article = $this->articleManager->showById((int) $this->request->get('idText'));
        $magazine = $this->magManager->showByIdAndPub($idMag);
        $token = $this->noCsrf->createToken();

        if ($article === null) {
            header("Location: index.php?action=listMag");
            exit();
        }

        $this->view->render(
            [
            'template' => 'back/pannelArticle',
            'data' => [
                'magazine' => $magazine,
                'article' => $article,
                'token' => $token,
                'message' => $message,
                ],
            ],
        );
    }

    public function createNewArticle(int $idMag): void
    {
        $this->auth->requireRole(1);

        $magazine = $this->magManager->showByIdAndPub($idMag);
        $article = $this->articleManager->createArticleByIdMag((int) $magazine->id_mag);
        $token = $this->noCsrf->createToken();

        if ($article === null) {
            header("Location: index.php?action=listMag");
            exit();
        }

        $this->view->render(
            [
            'template' => 'back/pannelArticle',
            'data' => [
                'magazine' => $magazine,
                'article' => $article,
                'token' => $token,
                ],
            ],
        );
    }

    public function addContent(int $idMag): void
    {
        $this->auth->requireRole(1);

        $idText = (int) $this->request->get('idText');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
            exit();
        };
 
        $this->articleManager->addContent((int) $this->request->get('idText'), (string) $this->request->post('content'));

        $magazine = $this->magManager->showByIdAndPub($idMag);
        $article = $this->articleManager->showById((int) $this->request->get('idText'));
        $token = $this->noCsrf->createToken();
        $message = 'Le contenu de l\'article a été modifié';

        $this->view->render(
            [
            'template' => 'back/pannelArticle',
            'data' => [
                'magazine' => $magazine,
                'article' => $article,
                'token' => $token,
                'message' => $message,
                ],
            ],
        );
    }

    public function modifyArticle():void
    {
        $this->auth->requireRole(1);

        $message = null;

        $idText = (int) $this->request->get('idText');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
            exit();
        };

        $this->dataLoader->addData('articleManager', 'idText', 'modifRubric', 'rubric', 'La rubrique a été modifiée', 'pannelarticle', 'findArticleById');

        $this->dataLoader->addData('articleManager', 'idText', 'modifTitle', 'title', 'Le titre a été modifiée', 'pannelarticle', 'findArticleById');

        $this->dataLoader->addData('articleManager', 'idText', 'modifAuthor', 'author', "L'auteur a été modifiée", 'pannelarticle', 'findArticleById');

        $this->dataLoader->addData('articleManager', 'idText', 'modifTeaser', 'teaser', "Le teaser a été modifiée", 'pannelarticle', 'findArticleById');

        $this->files->addFiles('articleManager', 'modifCover', 'articleCover', 'idText', "L'image a été modifiée", 'pannelarticle', 'findArticleById');
    }
}
