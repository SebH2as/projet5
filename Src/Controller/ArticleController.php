<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\Tools\Session;
use Projet5\View\View;

final class ArticleController
{
    private MagManager $magManager;
    private View $view;
    private ArticleManager $articleManager;
    private Request $request;
    private Auth $auth;
    private NoCsrf $noCsrf;
    private Session $session;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, View $view, Request $request, NoCsrf $noCsrf, Auth $auth, Session $session)
    {
        $this->magManager = $magManager;
        $this->view = $view;
        $this->articleManager = $articleManager;
        $this->request = $request;
        $this->auth = $auth;
        $this->noCsrf = $noCsrf;
        $this->session = $session;
    }

    //index.php?action=article&idMag=148&idText=3
    public function article(int $idMag, int $idText):void//méthode pour afficher la page d'un article
    {
        $user = $this->auth->user();
        $article = $this->articleManager->showById($idText);
        $magazine = $this->magManager->showByIdAndPub($idMag);

        if ($article === null || $magazine === null) {
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
    public function pannelArticle(int $idMag, int $idText): void
    {
        $this->auth->requireRole(1);

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = $this->request->get('message');
        }

        $error = $this->request->get('error');

        $article = $this->articleManager->showById($idText);
        $magazine = $this->magManager->showById($idMag);
        $token = $this->noCsrf->createToken();

        if ($article === null || $magazine === null) {
            $error = 'Le magazine ou l\'article demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
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
                'error' => $error,
                ],
            ],
        );
    }

    //index.php?action=pannelArticle&idMag=102&idText=169
    public function createNewArticle(int $idMag): void
    {
        $this->auth->requireRole(1);

        $magazine = $this->magManager->showById($idMag);

        if ($magazine === null) {
            $error = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $newArticle = $this->articleManager->createArticle($idMag);

        if (!$newArticle) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=pannelMag&idMag=$idMag&error=$error");
            exit();
        }

        $article = $this->articleManager->showMostRecentArticle();
        $idText = $article->getId_text();
        $message = 'Nouvel article créé';

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    //index.php?action=pannelArticle&idMag=102&idText=170&message=Le%20contenu%20de%20l%27article%20a%20été%20modifié
    public function addContent(int $idMag, int $idText): void
    {
        $this->auth->requireRole(1);

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
            exit();
        };
 
        $update = $this->articleManager->updateContent($idText);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&error=$error");
            exit();
        }

        $message = 'Le contenu de l\'article a été modifié';

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    //index.php?action=pannelArticle&idMag=102&idText=170&message=L%27%20auteur%20de%20l%27article%20a%20été%20modifié
    public function modifyArticle(int $idMag, int $idText):void
    {
        $this->auth->requireRole(1);

        $message = null;

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
            exit();
        }

        $update = $this->articleManager->updateArticle($idText);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&error=$error");
            exit();
        }

        $message = $this->session->getSessionData('message');
        $this->session->setSessionData('message', null);

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    public function confirmDeleteArticle(int $idMag, int $idText):void
    {
        $this->auth->requireRole(1);
        
        $article = $this->articleManager->showById($idText);
        $magazine = $this->magManager->showById($idMag);

        if ($article === null || $magazine === null) {
            $error = 'Le magazine ou l\'article demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }
        
        $this->view->render(
            [
            'template' => 'back/confirmDeleteArticle',
            'data' => [
                'magazine' => $magazine,
                'article' => $article,
                ],
            ],
        );
    }

    //index.php?action=pannelMag&idMag=102&message=L%27article%20a%20été%20supprimmé
    public function deleteArticle(int $idMag, int $idText):void
    {
        $this->auth->requireRole(1);

        $message = null;

        $delete = $this->articleManager->deleteArticle($idText);

        if (!$delete) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $message ='L\'article a été supprimmé';
        
        header("Location: index.php?action=pannelMag&idMag=$idMag&message=$message");
        exit();
    }

    //index.php?action=pannelArticle&idMag=102&idText=135&message=L%27article%20a%20été%20passé%20à%20la%20une
    public function changeMain(int $idMag, int $idText):void
    {
        $this->auth->requireRole(1);
        $message = null;

        $update = $this->articleManager->updateMain($idText, $idMag);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&error=$error");
            exit();
        }

        $message = $this->session->getSessionData('message');
        $this->session->setSessionData('message', null);

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    
    public function previewArticle(int $idMag, int $idText):void
    {
        $this->auth->requireRole(1);
        
        $article = $this->articleManager->showById($idText);
        $magazine = $this->magManager->showById($idMag);

        if ($article === null || $magazine === null) {
            $error = 'Le magazine ou l\'article demandé n\'existe pas';
            header("Location: index.php?action=listMag&error=$error");
            exit();
        }

        $this->view->render(
            [
            'template' => 'back/previewArticle',
            'data' => [
                'magazine' => $magazine,
                'article' => $article,
                'preview' => 1,
                'active' => 0,
                ],
            ],
        );
    }
}
