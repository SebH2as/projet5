<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Model\Manager\UsersManager;
use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\View\View;

final class UsersController
{
    private MagManager $magManager;
    private LettersManager $lettersManager;
    private View $view;
    private ArticleManager $articleManager;
    private Request $request;
    private NoCsrf $noCsrf;
    private Auth $auth;

    public function __construct(UsersManager $usersManager, MagManager $magManager, LettersManager $lettersManager, View $view)
    {
        $this->magManager = $magManager;
        $this->lettersManager = $lettersManager;
        $this->view = $view;
        $this->usersManager = $usersManager;
        $this->request = new Request();
        $this->noCsrf = new noCsrf();
        $this->auth = new auth();
    }

    //index.php?action=connectionPage&idMag=122&error=true
    //index.php?action=monCompte&idMag=122
    public function connection(int $idMag):void //méthode pour se connecter au compte utilisateur ou au backoffice
    {
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf'))) {
            if ($this->request->post('pseudo') !== null &&  !empty($this->request->post('pseudo'))
            && $this->request->post('password') !== null &&  !empty($this->request->post('password'))) {
                $user = $this->auth->login($this->request->post('pseudo'), $this->request->post('password'));

                if ($user === null) {
                    header("Location: index.php?action=connectionPage&idMag=$idMag&error=true");//rajouter l'erreur
                    exit();
                }

                if ($user->role === 1) {
                    header("Location: index.php?action=listMag&idMag=$idMag");
                    exit();
                }

                header("Location: index.php?action=monCompte&idMag=$idMag");
                exit();
            }
        }
    }

    //index.php?action=monCompte&idMag=122
    public function monCompte(int $idMag):void//méthode pour afficher la page mon compte si l'utilisateur est connecté
    {
        $message = null;
        
        $messageContent = ['Votre mot de passe a bien été modifié',
        'Votre courrier a été enregistré. Il est en attente de validation',
        'Vous êtes maintenant abonné à notre newsletter',
        'Votre abonnememt à notre newsletter a été annulé',
        'Votre pseudo a bien été modifié',
        'votre Email a bien été modifié'];
        
        if ($this->request->get('message') !== null) {
            $message = $messageContent[(int) $this->request->get('message')];
        }
        
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }
            
        if ($user->role === 1) {
            $this->adminProfil();//mettre un header
            exit();
        }
            
        $nbUnpubletters = $this->lettersManager->countUnpubById((int) $user->id_user);
        $nbPubletters = $this->lettersManager->countPubById((int) $user->id_user);
        $magazine = $this->magManager->showByIdAndPub($idMag);
        
        $this->view->render(
            [
            'template' => 'front/monCompte',
            'data' => [
                'magazine' => $magazine,
                'preview' => 0,
                'active' =>0,
                'user' => $user,
                'nbUnpubletters' => $nbUnpubletters[0],
                'nbPubletters' => $nbPubletters[0],
                'message' => $message,
                ],
            ],
        );
    }

    //index.php
    public function userDeco():void//méthode pour se déconnecter en tant qu'utilisateur
    {
        session_unset();
        session_destroy();
        session_write_close();
        header('location: index.php');
    }

    //index.php?action=monCompte&idMag=122&message=2
    public function newsLetterAbo(int $idMag):void//méthode pour s'abonner ou se désabonner à la newsletter
    {
        $user = $this->auth->user();
        if ($user->newsletter === 0) {
            $this->usersManager->getAboNewsletter((int) $user->id_user, 1);
            header("Location: index.php?action=monCompte&idMag=$idMag&message=2");
            exit();
        }
        $this->usersManager->abortAboNewsletter((int) $user->id_user, 0);
        header("Location: index.php?action=monCompte&idMag=$idMag&message=3");
        exit();
    }

    //index.php?action=modifUser&idMag=122&userData=Pseudo
    public function modifUser(int $idMag):void//méthode pour accéder à une page de modification d'une des données de l'utilisateur
    {
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }
        
        $magazine = $this->magManager->showByIdAndPub($idMag);
        $token = $this->noCsrf->createToken();
        $userData = (string) $this->request->get('userData');
        $error = null;
        
        $this->view->render(
            [
            'template' => 'front/modifDataUser',
            'data' => [
                'magazine' => $magazine,
                'preview' => 0,
                'active' =>0,
                'user' => $user,
                'token' => $token,
                'error' => $error,
                'userData' => $userData,
                ],
            ],
        );
    }

    
    public function modifPassword(int $idMag):void//méthode pour modifier son mot de base en tant qu'utilisateur A RETRAVAILLER
    {
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }

        $error = 'Une erreur est survenue, veuillez recommencer vos modifications';

        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf'))) {
            if ($this->request->post('password') !== null && !empty($this->request->post('password'))
                && password_verify($this->request->post('password'), $user->p_w)) {
                if ($this->request->post('new') !== null && !empty($this->request->post('new'))) {
                    $error = 'Le nouveau mot de passe choisi n\'est pas valide';
                    if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('new'))) {
                        $error = 'Les deux mots de passe renseignés ne correspondent pas';
                        if ($this->request->post('new') === $this->request->post('new2')) {
                            //$this->usersManager->modifPass((int) $user->id_user, (string) password_hash($this->request->post('new'), PASSWORD_DEFAULT));
                            header("Location: index.php?action=monCompte&idMag=$idMag&message=0");
                            exit();
                        }
                    }
                }
            }
        }
            
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $token = $this->noCsrf->createToken();
        $this->view->render('front/modifPassUser', 'front/layout', compact('magazine', 'user', 'error', 'token'));
        exit();
    }
}
