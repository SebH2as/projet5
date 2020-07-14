<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Model\UsersManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;
use Projet5\Tools\Session;


class UserController{
        
    private $magManager;
    private $articleManager;
    private $usersManager;
    private $view;
    private $request;
    private $dataLoader;
    private $files;
    private $session;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->usersManager = new usersManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
        $this->files = new files();
        $this->session = new session();
    }

    public function monCompte():void//méthode pour afficher la page mon compte
    {
        $this->session->userControl();
        $userSession = $this->session->getSessionData('userConnected');
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/monCompte', 'front/layout', compact('magazine', 'userSession'));
        
    }

    public function nousRejoindre():void//méthode pour afficher la page mon compte
    {
        $error = null;
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/nousRejoindre', 'front/layout', compact('magazine', 'error'));
    }

    public function addUser():void
    {
        $isError = true;
        
        $error = 'Au moins un des champs est vide';
        if($this->request->post('pseudo') !== null &&  !empty($this->request->post('pseudo'))
        && $this->request->post('mail') !== null &&  !empty($this->request->post('mail'))
        && $this->request->post('mail2') !== null &&  !empty($this->request->post('mail2'))
        && $this->request->post('password') !== null &&  !empty($this->request->post('password'))
        && $this->request->post('password2') !== null &&  !empty($this->request->post('password2')))
        {
            $error = 'Le pseudo choisi n\'est pas valide';
            if(strlen($this->request->post('pseudo')) > 3 && strlen($this->request->post('pseudo')) < 15)
            {
                $error = 'Le pseudo choisi est déjà utilisé';
                $pseudoThere = $this->usersManager->pseudoUser( $this->request->post('pseudo'));
                if( ($pseudoThere[0]) < 1)
                {
                    $error = 'Les emails renseignés ne correspondent pas';
                    if(($this->request->post('mail')) === ($this->request->post('mail2')))
                    {
                        $error = 'L\' email choisi est déjà utilisé';
                        $emailThere = $this->usersManager->emailUser( $this->request->post('mail'));
                        if( ($emailThere[0]) < 1)
                        {
                            $error = 'Les mots de passe renseignés ne correspondent pas'; 
                            if(($this->request->post('password')) === ($this->request->post('password2')))
                            {
                                $error = 'Le mot de passe choisi n\'est pas valide';
                                if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('password')))
                                {
                                    $error =null;
                                    $key = '';
                                    for($i = 1; $i<6 ; $i++)
                                    {
                                        $key .= mt_rand(0,9);
                                    }

                                    $this->usersManager->addUser((string) $this->request->post('pseudo'),(string) $this->request->post('mail'),(string) password_hash($this->request->post('password'), PASSWORD_DEFAULT),(int) $key);
                                    $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
                                    $this->view->render('front/activation', 'front/layout', compact('magazine', 'error'));
                                    var_dump($this->usersManager->pseudoUser($this->request->post('pseudo')));
                                    exit();
                                }
                                
                            }
                        }
                        
                    }
                }
            }  
        }
        if($isError)
        {
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/nousRejoindre', 'front/layout', compact('magazine', 'error'));
        }
        
    }

    public function activation()
    {
        $isError = true;
        
        $error = 'Veuillez renseigner tous les champs';
        if($this->request->post('pseudo') !== null &&  !empty($this->request->post('pseudo'))
        && $this->request->post('code') !== null &&  !empty($this->request->post('code')))
        {
            $error = 'Le code renseigné n\'est pas valide';
            $userKey = $this->usersManager->getKeyByPseudo($this->request->post('pseudo'));
            if(($this->request->post('code')) === $userKey[0]->confirmkey)
            {
                $this->usersManager->activeCount($this->request->post('pseudo'));
                $this->session->setSessionData('userConnected', '1');
                $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
                $this->view->render('front/monCompte', 'front/layout', compact('magazine'));
                exit();
            }
        }

        if($isError)
        {
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/activation', 'front/layout', compact('magazine', 'error'));
        }
    }

    public function connectionPage():void//méthode pour se connecter au back
    {
        $error = null;
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/connectionPage', 'front/layout', compact('magazine', 'error'));
        
    }

    public function connection()
    {
        $error = 'Une erreur est survenue, veuillez recommencer';
        if($this->request->post('pseudo') !== null &&  !empty($this->request->post('pseudo'))
        && $this->request->post('mail') !== null &&  !empty($this->request->post('mail'))
        && $this->request->post('password') !== null &&  !empty($this->request->post('password')))
        {
            $user = $this->usersManager->getUserByPseudo($this->request->post('pseudo'));
            if(!empty($user))
            {
                if($user[0]->p_w !== null){
                    if(password_verify(((string) $this->request->post('password')), (string) $user[0]->p_w))
                    {
                        $this->session->setSessionData('userConnected', '1');
                        $this->session->setSessionData('pseudo', $user[0]->pseudo);
                        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
                        $this->view->render('front/monCompte', 'front/layout', compact('magazine', 'error'));
                        exit();
                    }  
                }
            }
        }
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/connectionPage', 'front/layout', compact('magazine', 'error'));
    }


}