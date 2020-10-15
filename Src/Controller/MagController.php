<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Request;
use Projet5\View\View;

final class MagController
{
    private MagManager $magManager;
    private ArticleManager $articleManager;
    private LettersManager $lettersManager;
    private View $view;
    private Request $request;

    public function __construct(MagManager $magManager, ArticleManager $articleManager, LettersManager $lettersManager, View $view)
    {
        $this->magManager = $magManager;
        $this->articleManager = $articleManager;
        $this->lettersManager = $lettersManager;
        $this->view = $view;
        $this->request = new request();
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

    public function courrier(int $idMag):void//méthode pour afficher la page courrier d'un magazine
    {
        $magazine = $this->magManager->showByIdAndPub($idMag);
        
        $totalLetters =  $this->lettersManager->countLettersByRelatedMag($magazine->numberMag);
        $nbByPage = 2;
        $totalpages = (int) ceil($totalLetters[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
        }

        $offset = ($currentpage - 1) * $nbByPage;
        
        $letters = $this->lettersManager->showByRelatedMag((int) $offset, (int) $nbByPage, (int) $magazine->numberMag);
        
        $this->view->render(
            [
            'template' => 'front/courrier',
            'data' => [
                'magazine' => $magazine,
                'letters' => $letters,
                'preview' => 0,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                ],
            ],
        );
    }

    public function quiSommesNous(int $idMag):void//méthode pour afficher la page Qui sommes nous?
    {
        $magazine = $this->magManager->showByIdAndPub($idMag);

        $this->view->render(
            [
            'template' => 'front/quiSommesNous',
            'data' => [
                'magazine' => $magazine,
                'preview' => 0,
                'active' => 0,
                ],
            ],
        );
    }
}
