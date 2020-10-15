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

    public function __construct(MagManager $magManager, ArticleManager $articleManager, View $view)
    {
        $this->magManager = $magManager;
        $this->view = $view;
        $this->articleManager = $articleManager;
    }

    public function lastMagazine(): void//méthode pour afficher la page d'accueil et récupérer le dernier magasine publié avec ses articles
    {
        $magazine = $this->magManager->showLastAndPub();
        $articles = $this->articleManager->showByIdmag($magazine->id_mag);

        $previous = $this->magManager->showByNumber($magazine->numberMag - 1);
        $next = $this->magManager->showByNumber($magazine->numberMag + 1);

        $this->view->render(
            [
            'template' => 'front/magazine',
            'data' => [
                'magazine' => $magazine,
                'articles' => $articles,
                'preview' => 0,
                'active' => 1,
                'previous' => $previous,
                'next' => $next,
                ],
            ],
        );
    }

    public function magByNumber(int $numberMag): void
    {
        $magazine = $this->magManager->showByNumber($numberMag);
        if ($magazine) {
            $articles = $this->articleManager->showByIdmag($magazine->id_mag);
            
            $next = $this->magManager->showByNumber($magazine->numberMag + 1);
            $previous = $this->magManager->showByNumber($magazine->numberMag - 1);
            $this->view->render(
                [
                'template' => 'front/magazine',
                'data' => [
                    'magazine' => $magazine,
                    'articles' => $articles,
                    'preview' => 0,
                    'active' => 1,
                    'previous' => $previous,
                    'next' => $next,
                    ],
                ],
            );
            exit();
        }
        $this->lastMagazine();
    }

    public function editorial(int $idMag):void//méthode pour afficher la page de l'éditorial d'un magazine
    {
        $magazine = $this->magManager->showByIdAndPub($idMag);
        
        $this->view->render(
            [
            'template' => 'front/editorial',
            'data' => [
                'magazine' => $magazine,
                'preview' => 0,
                'active' => 0,
                ],
            ],
        );
    }
}
