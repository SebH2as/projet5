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

    public function __construct(MagManager $magManager, ArticleManager $articleManager, View $view)
    {
        $this->magManager = $magManager;
        $this->view = $view;
        $this->articleManager = $articleManager;
        $this->request = new Request();
        $this->auth = new auth();
        $this->noCsrf = new NoCsrf();
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

    //index.php?action=pannelArticle&idMag=102&idText=169
    public function createNewArticle(int $idMag): void
    {
        $this->auth->requireRole(1);

        $magazine = $this->magManager->showByIdAndPub($idMag);
        $articleNew = $this->articleManager->createArticleByIdMag((int) $magazine->id_mag);
        $token = $this->noCsrf->createToken();

        if ($articleNew === null) {
            header("Location: index.php?action=listMag");
            exit();
        }

        $article = $this->articleManager->showMostRecentArticle();
        $idText = $article->id_text;
        $message = 'Nouvel article créé';

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    //index.php?action=pannelArticle&idMag=102&idText=170&message=Le%20contenu%20de%20l%27article%20a%20été%20modifié
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

        $message = 'Le contenu de l\'article a été modifié';

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    //index.php?action=pannelArticle&idMag=102&idText=170&message=L%27%20auteur%20de%20l%27article%20a%20été%20modifié
    public function modifyArticle(int $idMag):void
    {
        $this->auth->requireRole(1);

        $message = null;

        $idText = (int) $this->request->get('idText');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
            exit();
        }

        if ($this->request->post('rubric') !== null && !empty($this->request->post('rubric'))
        && !empty($this->request->post('modifRubric'))) {
            $message = 'La rubrique de l\'article a été modifié';
            $this->articleManager->modifTextType($idText, $this->request->post('rubric'));
        }

        if ($this->request->post('title') !== null && !empty($this->request->post('title'))
        && !empty($this->request->post('modifTitle'))) {
            $message = 'Le titre de l\'article a été modifié';
            $this->articleManager->modifTitle($idText, $this->request->post('title'));
        }

        if ($this->request->post('author') !== null && !empty($this->request->post('author'))
        && !empty($this->request->post('modifAuthor'))) {
            $message = 'L\' auteur de l\'article a été modifié';
            $this->articleManager->modifAuthor($idText, $this->request->post('author'));
        }

        if ($this->request->post('teaser') !== null && !empty($this->request->post('teaser'))
        && !empty($this->request->post('modifTeaser'))) {
            $message = 'Le teaser de l\'article a été modifié';
            $this->articleManager->modifTeaser($idText, $this->request->post('teaser'));
        }

        if (!empty($this->request->post('modifCover'))) {
            $cover = $_FILES['articleCover'];
            $ext = mb_strtolower(mb_substr($cover['name'], -3)) ;
            $allowExt = ["jpg", "png"];
            
            if (in_array($ext, $allowExt, true)) {
                $dataToErase = $this->articleManager->showById($idText);
                
                if (($dataToErase->articleCover) !== null) {
                    unlink("../public/images/".$dataToErase->articleCover);
                }
                
                move_uploaded_file($cover['tmp_name'], "../public/images/".$cover['name']);
                $message = 'La couverture de l\'article a été modifié';
                $this->articleManager->modifCover($idText, (string) $cover['name']);
            }
        }

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    //index.php?action=pannelMag&idMag=102&message=L%27article%20a%20été%20supprimmé
    public function deleteArticle(int $idMag):void
    {
        $this->auth->requireRole(1);

        $idText = (int) $this->request->get('idText');

        $message = null;

        $dataToErase = $this->articleManager->showById($idText);

        if (($dataToErase->articleCover) !== null) {
            unlink("../public/images/".$dataToErase->articleCover);
        }

        $this->articleManager->deleteArticle($idText);

        $message ='L\'article a été supprimmé';
        
        header("Location: index.php?action=pannelMag&idMag=$idMag&message=$message");
        exit();
    }

    //index.php?action=pannelArticle&idMag=102&idText=135&message=L%27article%20a%20été%20passé%20à%20la%20une
    public function changeMain(int $idMag):void
    {
        $this->auth->requireRole(1);

        $idText = (int) $this->request->get('idText');

        $article = $this->articleManager->showById($idText);

        if ($article->main === 0) {
            $this->articleManager->unsetMainAllArticles($idMag);
            $this->articleManager->changeMain($idText, 1);

            $message = 'L\'article a été passé à la une';
        }

        if ($article->main === 1) {
            $this->articleManager->changeMain($idText, 0);

            $message = 'L\'article a été retiré de la une';
        }

        header("Location: index.php?action=pannelArticle&idMag=$idMag&idText=$idText&message=$message");
        exit();
    }

    
    public function previewArticle(int $idMag):void
    {
        $this->auth->requireRole(1);
        
        $article = $this->articleManager->showById((int) $this->request->get('idText'));
        $magazine = $this->magManager->showById($idMag);

        if ($article === null) {
            header("Location: index.php");
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
