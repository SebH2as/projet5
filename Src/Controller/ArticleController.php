<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\View\View;

final class ArticleController
{
    private MagManager $magManager;
    private View $view;
    private ArticleManager $articleManager;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, View $view)
    {
        $this->magManager = $magManager;
        $this->view = $view;
        $this->articleManager = $articleManager;
    }

    public function article(int $idText):void//méthode pour afficher la page d'un article
    {
        $article = $this->articleManager->ShowById($idText);
        $magazine = $this->magManager->showByIdAndPub($article->id_mag);

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