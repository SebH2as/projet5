<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Auth;
use Projet5\Tools\Request;
use Projet5\View\View;

final class ArticleController
{
    private MagManager $magManager;
    private View $view;
    private ArticleManager $articleManager;
    private Request $request;
    private Auth $auth;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, View $view)
    {
        $this->magManager = $magManager;
        $this->view = $view;
        $this->articleManager = $articleManager;
        $this->request = new Request();
        $this->auth = new auth();
    }

    //index.php?action=article&value=148&value2=3
    public function article(int $idText, int $idMag):void//méthode pour afficher la page d'un article
    {
        $user = $this->auth->user();
        $article = $this->articleManager->ShowById($idText);
        $magazine = $this->magManager->showByNumber($idMag);
        if ($article) {
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
    }

    //index.php?action=articles&value=122&value2=2
    public function articles(int $idMag, int $textType):void//méthode pour afficher la page récapitulatrice de toutes les chroniques publiées
    {
        $type = ['',
                '',
                'Chronique',
                'Essai',
                'Fiction'];
        
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
                'textType' => $type[$textType],
                ],
            ],
        );
    }
}
