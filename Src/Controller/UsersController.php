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

    public function connection(int $idMag):void //méthode pour se connecter au compte utilisateur ou au backoffice
    {
        $error = 'Pseudo ou mot de passe incorrect';
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf'))) {
            if ($this->request->post('pseudo') !== null &&  !empty($this->request->post('pseudo'))
            && $this->request->post('password') !== null &&  !empty($this->request->post('password'))) {
                $user = $this->auth->login($this->request->post('pseudo'), $this->request->post('password'));
                if ($user) {
                    if ($user->role === 0) {
                        $this->monCompte($idMag);
                        exit();
                    }
                    if ($user->role === 1) {
                        $this->magController->listMag();
                        exit();
                    }
                }
            }
        }
    }

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
        
        if ($user) {
            if ($user->role === 0) {
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
                        ],
                    ],
                );
                exit();
            }
            if ($user->role === '1') {
                $this->adminProfil();
                exit();
            }
        }
        header('location: index.php');
        exit();
    }

    public function userDeco():void
    {
        session_unset();
        session_destroy();
        session_write_close();
        header('location: index.php');
    }
}
