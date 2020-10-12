<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\View\View;

final class MagController
{
    private MagManager $magManager;
    private View $view;
    private ArticleManager $articleManager;

    public function __construct(MagManager $magManager, ArticleManager $articleManager)
    {
        $this->magManager = $magManager;
        $this->view = new View();
        $this->articleManager = $articleManager;
    }

    public function lastMagazine():void//méthode pour afficher la page d'accueil et récupérer le dernier épisode posté
    {
        $magazine = $this->magManager->showLastAndPub();
        $articles = $this->articleManager->showByIdmag($magazine->id_mag);

        $this->view->render('front/magazine', 'front/layout', compact('magazine', 'articles'));
        
    }
}