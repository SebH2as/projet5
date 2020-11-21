<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Model\Manager\NewslettersManager;
use Projet5\Tools\Auth;
use Projet5\Tools\DataLoader;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\Tools\Session;
use Projet5\View\View;

final class LettersController
{
    private MagManager $magManager;
    private ArticleManager $articleManager;
    private LettersManager $lettersManager;
    private NewslettersManager $newslettersManager;
    private View $view;
    private Request $request;
    private NoCsrf $noCsrf;
    private Auth $auth;
    private Session $session;

    public function __construct(MagManager $magManager, LettersManager $lettersManager, NewslettersManager $newslettersManager, View $view, Request $request, NoCsrf $noCsrf, Auth $auth, Session $session)
    {
        $this->magManager = $magManager;
        $this->lettersManager = $lettersManager;
        $this->newslettersManager = $newslettersManager;
        $this->view = $view;
        $this->request = $request;
        $this->auth = $auth;
        $this->noCsrf = $noCsrf;
        $this->session = $session;
    }

    //index.php?action=nousEcrire&idMag=122
    public function nousEcrire(int $idMag): void//Méthode pour afficher la page de rédaction d'un courrier utilisateur
    {
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }
        
        $error = null;
        if ($this->request->get('error') !== null) {
            $error = $this->request->get('error');
        }

        $magazine = $this->magManager->showByIdAndPub($idMag);

        if ($magazine === null) {
            header('location: index.php');
            exit();
        }

        $numberMags = $this->magManager->showPubNumberMag();
        $token = $this->noCsrf->createToken();

        $this->view->render(
            [
            'template' => 'front/nousEcrire',
            'data' => [
                'magazine' => $magazine,
                'preview' => 0,
                'active' =>0,
                'token' => $token,
                'error' =>$error,
                'user' =>$user,
                'numberMags' => $numberMags
                ],
            ],
        );
    }

    //index.php?action=postLetter&idMag=122
    public function postLetter(int $idMag): void//Méthode pour poster un courrier utilisateur
    {
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }
        
        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $error = "Une erreur est survenue, veuillez recommencer";

            header("Location: index.php?action=nousEcrire&idMag=$idMag&error=$error");
            exit();
        }

        $newLetter = $this->lettersManager->createLetter((int) $user->getId_user(), (string) $user->getPseudo());

        if (!$newLetter) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=nousEcrire&idMag=$idMag&error=$error");
            exit();
        }

        header("Location: index.php?action=monCompte&idMag=$idMag&message=1");
        exit();
    }

    //index.php?action=courrier&idMag=110
    public function courrier(int $idMag):void//méthode pour afficher la page courrier des lecteurs
    {
        $user = $this->auth->user();
        $magazine = $this->magManager->showByIdAndPub($idMag);

        if ($magazine === null) {
            header('location: index.php');
            exit();
        }
        
        $totalLetters =  $this->lettersManager->countPubLetters();
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
        
        $letters = $this->lettersManager->showPubLetters((int) $offset, (int) $nbByPage);
        
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

        $totalLetters =  $this->lettersManager->countPubLetters();
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
        
        $letters = $this->lettersManager->showPubLetters((int) $offset, (int) $nbByPage);
        
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

        $error = null;
        if ($this->request->get('error') !== null) {
            $error = htmlspecialchars($this->request->get('error'));
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
                'error' => $error,
                ],
            ],
        );
    }

    //index.php?action=relatedMag&idLetter=42
    public function relatedMag(int $idMag): void//méthode pour modifier le thème associé à un courrier
    {
        $this->auth->requireRole(1);
        $message = null;
        $idLetter = (int)$this->request->get('idLetter');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $error = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=lettersBack&error=$error");
            exit();
        }

        $update = $this->lettersManager->updateRelatedMag($idLetter);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=letterBack&idLetter=$idLetter&error=$error");
            exit();
        }
        
        $message = 'La thématique du courrier a été modifiée';
            

        header("Location: index.php?action=letterBack&idLetter=$idLetter&message=$message");
        exit();
    }

    //index.php?action=setPublished&idLetter=42
    public function setPublished(int $idMag): void //méthode pour publier un courrier
    {
        $this->auth->requireRole(1);

        $idLetter = (int)$this->request->get('idLetter');
        $message = null;
        
        $update = $this->lettersManager->updatePublished($idLetter);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=letterBack&idLetter=$idLetter&error=$error");
            exit();
        }

        $message = $this->session->getSessionData('message');
        $this->session->setSessionData('message', null);

        header("Location: index.php?action=letterBack&idLetter=$idLetter&message=$message");
        exit();
    }

    //index.php?action=courrierDelete&idLetter=42
    public function confirmDeleteLetter():void //méthode pour afficher la page de confirmation de suppression d'un courrier
    {
        $this->auth->requireRole(1);

        $idLetter = (int)$this->request->get('idLetter');
        $letter = $this->lettersManager->showLetterById($idLetter);

        $this->view->render(
            [
            'template' => 'back/confirmDeleteLetter',
            'data' => [
                'letter' => $letter,
                ],
            ],
        );
    }

    //index.php?action=courrierDelete&idLetter=42
    public function courrierDelete(int $idMag):void //méthode pour supprimer un courrier
    {
        $this->auth->requireRole(1);

        $idLetter = (int)$this->request->get('idLetter');

        $delete = $this->lettersManager->deleteLetterById($idLetter);

        if (!$delete) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=letterBack&idLetter=$idLetter&error=$error");
            exit();
        }

        $message = $this->session->getSessionData('message');
        $this->session->setSessionData('message', null);

        header("Location: index.php?action=lettersBack&message=$message");
        exit();
    }

    //index.php?action=setResponse&idLetter=42
    public function setResponse(int $idMag):void //méthode pour répondre à un courrier
    {
        $this->auth->requireRole(1);

        $message = null;

        $idLetter = (int)$this->request->get('idLetter');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $message = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=createNewMag");
            exit();
        }

        $update = $this->lettersManager->updateResponse($idLetter);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=letterBack&idLetter=$idLetter&error=$error");
            exit();
        }

        $message = 'La réponse au courrier a été enregistrée';

        header("Location: index.php?action=letterBack&idLetter=$idLetter&message=$message");
        exit();
    }

    //index.php?action=newslettersBack
    public function newslettersBack(int $idMag): void//méthode pour afficher la page récapitulatrice de toutes les newsletters dans le back
    {
        $this->auth->requireRole(1);

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

        $totalLetters = $this->newslettersManager->countAllNewsletters();
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

        $newsletters = $this->newslettersManager->showAllNewsletters((int) $offset, (int) $nbByPage);
        
        $this->view->render(
            [
            'template' => 'back/newslettersBack',
            'data' => [
                'newsletters' => $newsletters,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                'message' => $message,
                ],
            ],
        );
    }

    //index.php?action=newNewsletter
    public function newNewsletter(): void //méthode créer une newsletter
    {
        $this->auth->requireRole(1);

        $this->newslettersManager->createNewsletter();

        $message = 'Une newsletter a bien été créée';

        header("Location: index.php?action=newslettersBack&message=$message");
        exit();
    }

    //index.php?action=newsletterBack&idNewsletter=7
    public function newsletterBack(): void //méthode pour afficher la page d'une newsletter dans le back
    {
        $this->auth->requireRole(1);

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

        $error = null;
        if ($this->request->get('error') !== null) {
            $error = htmlspecialchars($this->request->get('error'));
        }

        $idNewsletter = (int)$this->request->get('idNewsletter');

        $newsletter = $this->newslettersManager->showNewslettersById($idNewsletter);
        $token = $this->noCsrf->createToken();

        $this->view->render(
            [
            'template' => 'back/newsletterBack',
            'data' => [
                'newsletter' => $newsletter,
                'token' => $token,
                'message' => $message,
                'error' => $error,
                ],
            ],
        );
    }

    //index.php?action=addContentNewsletter&idNewsletter=7
    public function addContentNewsletter(): void //méthode pour rédiger une newsletter
    {
        $this->auth->requireRole(1);

        $idNewsletter = (int)$this->request->get('idNewsletter');
        $newsletter = $this->newslettersManager->showNewslettersById($idNewsletter);

        if ($newsletter === null) {
            header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter");
            exit();
        }

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $error = "Une erreur est survenue, veuillez recommencer";
            header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter");
            exit();
        }

        $update = $this->newslettersManager->updateContent($idNewsletter);

        if (!$update) {
            $error = $this->session->getSessionData('error');
            $this->session->setSessionData('error', null);

            header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter&error=$error");
            exit();
        }

        $message = 'Le contenu de la newsletter a bien été mis à jour';

        header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter&message=$message");
        exit();
    }

    //index.php?action=deleteNewsletter&idNewsletter=7
    public function deleteNewsletter(): void //méthode pour supprimer une newsletter
    {
        $this->auth->requireRole(1);

        $idNewsletter = (int)$this->request->get('idNewsletter');

        $newsletter = $this->newslettersManager->showNewslettersById($idNewsletter);

        if ($newsletter === null) {
            header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter");
            exit();
        }

        $message = 'La newsletter a bien été supprimée';

        $this->newslettersManager->deleteNewsletterById($idNewsletter);

        header("Location: index.php?action=newslettersBack&message=$message");
        exit();
    }
}
