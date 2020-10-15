<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Request;
use Projet5\View\View;

final class ArticleController
{
    private MagManager $magManager;
    private View $view;
    private ArticleManager $articleManager;
    private Request $request;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, View $view)
    {
        $this->magManager = $magManager;
        $this->view = $view;
        $this->articleManager = $articleManager;
        $this->request = new request();
    }

    public function article(int $idText, int $idMag):void//méthode pour afficher la page d'un article
    {
        $article = $this->articleManager->ShowById($idText);
        $magazine = $this->magManager->showByNumber($idMag);
        if ($article) {
            $this->view->render(
                [
                'template' => 'front/article',
                'data' => [
                    'magazine' => $magazine,
                    'article' => $article,
                    'preview' => 0,
                    'active' => 0,
                    ],
                ],
            );
        }
    }

    public function articles(int $idMag, int $textType):void//méthode pour afficher la page récapitulatrice de toutes les chroniques publiées
    {
        $type = ['',
                '',
                'Chronique',
                'Fiction',
                'Essai'];
        
        $totalArticles = $this->articleManager->countPublishedByType($type[$textType]);
        $nbByPage = 6;
        $totalpages = (int) ceil($totalArticles[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $articles = $this->articleManager->showAllPublishedByType((string) $type[$textType], (int) $offset, (int) $nbByPage);
        $magazine = $this->magManager->showByIdAndPub($idMag);
        $this->view->render(
            [
            'template' => 'front/articles',
            'data' => [
                'magazine' => $magazine,
                'articles' => $articles,
                'preview' => 0,
                'active' => $textType,
                'currentpage' => $currentpage,
                'textType' => $type[$textType],
                ],
            ],
        );
    }
}
