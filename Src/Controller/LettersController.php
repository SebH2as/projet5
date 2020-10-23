<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Auth;
use Projet5\Tools\DataLoader;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\View\View;

final class LettersController
{
    private MagManager $magManager;
    private ArticleManager $articleManager;
    private LettersManager $lettersManager;
    private View $view;
    private Request $request;
    private NoCsrf $noCsrf;
    private Auth $auth;

    public function __construct(MagManager $magManager, LettersManager $lettersManager, View $view)
    {
        $this->magManager = $magManager;
        $this->lettersManager = $lettersManager;
        $this->view = $view;
        $this->request = new Request();
        $this->noCsrf = new NoCsrf();
        $this->auth = new Auth();
    }

    //index.php?action=courrier&idMag=110
    public function courrier(int $idMag):void//méthode pour afficher la page courrier d'un magazine
    {
        $user = $this->auth->user();
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
                'user' => $user,
                'magazine' => $magazine,
                'letters' => $letters,
                'preview' => 0,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                ],
            ],
        );
    }

    public function previewLetters(int $idMag):void//méthode pour afficher la page courrier d'un magazine en preview
    {
        $this->auth->requireRole(1);
        
        $magazine = $this->magManager->showById($idMag);

        if ($magazine === null) {
            header("Location: index.php");
            exit();
        }

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
            'template' => 'back/previewLetters',
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

    //index.php?action=lettersBack
    public function lettersBack(int $idMag): void//méthode pour afficher la page récapitulatrice de tout les courriers dans le back
    {
        $this->auth->requireRole(1);

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

        $totalLetters = $this->lettersManager->countAllLetters();
        $nbByPage = 5;
        $totalpages = (int) ceil($totalLetters[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
        }

        $offset = ($currentpage - 1) * $nbByPage;

        $letters = $this->lettersManager->showAllLetters((int) $offset, (int) $nbByPage);
        
        $this->view->render(
            [
            'template' => 'back/lettersBack',
            'data' => [
                'letters' => $letters,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                'message' => $message,
                ],
            ],
        );
    }

    //index.php?action=letterBack&idLetter=42
    public function letterBack(int $idMag): void//méthode pour afficher le panneau d'administration d'un courrier reçu dans le back
    {
        $this->auth->requireRole(1);

        $idLetter = (int)$this->request->get('idLetter');

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

        $token = $this->noCsrf->createToken();
        $numberMags = $this->magManager->showAllNumberMag();
        $letter = $this->lettersManager->showLetterById($idLetter);

        $this->view->render(
            [
            'template' => 'back/letterBack',
            'data' => [
                'letter' => $letter,
                'numberMags' => $numberMags,
                'token' => $token,
                'message' => $message,
                ],
            ],
        );
    }

    //index.php?action=letterBack&idLetter=42&message=Le%20courrier%20a%20été%20associé%20au%20magazine%20choisi
    public function relatedMag(int $idMag): void
    {
        $this->auth->requireRole(1);

        $idLetter = (int)$this->request->get('idLetter');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=createNewMag");
            exit();
        }

        if ($this->request->post('numberMag') !== null && !empty($this->request->post('numberMag'))
        && !empty($this->request->post('modifRelatedMag'))) {
            $message = 'Le courrier a été associé au magazine choisi';
            $this->lettersManager->setRelatedMag($idLetter, (int) $this->request->post('numberMag'));
        }

        header("Location: index.php?action=letterBack&idLetter=$idLetter&message=$message");
        exit();
    }

    //index.php?action=letterBack&idLetter=42&message=Le%20courrier%20a%20été%20retiré%20du%20magazine%20associé
    public function setPublished(int $idMag): void
    {
        $this->auth->requireRole(1);

        $idLetter = (int)$this->request->get('idLetter');

        $letter = $this->lettersManager->showLetterById($idLetter);

        if ($letter->magRelated === null) {
            $message = "Un numéro de magazine doit être associé au courrier avant de le publier";
            header("Location: index.php?action=letterBack&idLetter=$idLetter&message=$message");
            exit();
        }

        if ($letter->published === 0) {
            $this->lettersManager->setLetterPublished($idLetter, 1);
            $message = "Le courrier a été publié dans le magazine associé";
        }

        if ($letter->published === 1) {
            $this->lettersManager->setLetterPublished($idLetter, 0);
            $message = "Le courrier a été retiré du magazine associé";
        }

        header("Location: index.php?action=letterBack&idLetter=$idLetter&message=$message");
        exit();
    }

    public function courrierDelete(int $idMag):void
    {
        $this->auth->requireRole(1);

        $idLetter = (int)$this->request->get('idLetter');
        $letter = $this->lettersManager->showLetterById($idLetter);

        $message = 'Le courrier de '. $letter->author .' a été supprimé';

        $this->lettersManager->deleteLetterById($idLetter);

        header("Location: index.php?action=lettersBack&message=$message");
        exit();
    }

    public function setResponse(int $idMag):void
    {
        $this->auth->requireRole(1);

        $message = null;

        $idLetter = (int)$this->request->get('idLetter');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=createNewMag");
            exit();
        }

        if ($this->request->post('contentResponse') !== null && !empty($this->request->post('contentResponse'))
        && !empty($this->request->post('saveResponse'))) {
            $message = 'La réponse au courrier a été enregistrée';
            $this->lettersManager->setResponseById($idLetter, (string) $this->request->post('contentResponse'));
        }

        header("Location: index.php?action=letterBack&idLetter=$idLetter&message=$message");
        exit();
    }
}
