<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Controller\MagController;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Model\UsersManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;
use Projet5\Tools\Session;
use Projet5\Tools\Auth;


class UserController{
        
    private $magManager;
    private $magController;
    private $articleManager;
    private $usersManager;
    private $view;
    private $request;
    private $dataLoader;
    private $files;
    private $session;
    private $auth;

    public function __construct()
    {
        $this->view = new View();
        $this->magController = new magController();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->usersManager = new usersManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
        $this->files = new files();
        $this->session = new session();
        $this->auth = new auth();
    }

    public function monCompte():void//méthode pour afficher la page mon compte si l'utilisateur est connecté
    {
        $message = null;
        
        $messageContent = ['Votre mot de passe a bien été modifié',
        'Votre courrier a été enregistré. Il est en attente de validation',
        'Vous êtes maintenant abonné à notre newsletter',
        'Votre abonnememt à notre newsletter à été annulé',
        'Votre pseudo a bien été modifié',
        'votre Email a bien été modifié'];
        
        if($this->request->get('message') !== null)
        {
            $message = $messageContent[$this->request->get('message')];
        }
        
        $user = $this->auth->user();
        if($user)
        {
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/monCompte', 'front/layout', compact('magazine', 'user', 'message'));
            exit();
        }
        header('location: index.php');
        
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

    public function connectionPage():void //méthode pour afficher la page de connection
    {
        $error = null;
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/connectionPage', 'front/layout', compact('magazine', 'error'));
        
    }

    public function connection():void //méthode pour se connecter au compte utilisateur ou au backoffice
    {
        $error = 'Pseudo ou mot de passe incorrect';
        if($this->request->post('pseudo') !== null &&  !empty($this->request->post('pseudo'))
        && $this->request->post('password') !== null &&  !empty($this->request->post('password')))
        {
            $user = $this->auth->login($this->request->post('pseudo'), $this->request->post('password'));
            if($user)
            {
                if($user->role === '0')
                {
                    $this->monCompte();
                    exit();
                }
                if($user->role === '1')
                {
                    $this->magController->listMag();
                    exit();
                }
               
            }
        }
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/connectionPage', 'front/layout', compact('magazine', 'error'));
    }

    public function userDeco()
    {
        session_unset();
        session_destroy();
        session_write_close();
        header('location: index.php');
    }

    public function nousEcrire()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = null;
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/nousEcrire', 'front/layout', compact('magazine', 'user', 'error'));
            exit();
        }
        header('location: index.php');
    }

    public function modifPseudoUser()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = null;
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/modifPseudoUser', 'front/layout', compact('magazine', 'user', 'error'));
            exit();
        }
        header('location: index.php');
    }

    public function modifPassUser()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = null;
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/modifPassUser', 'front/layout', compact('magazine', 'user', 'error'));
            exit();
        }
        header('location: index.php');
    }

    public function modifEmailUser()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = null;
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/modifEmailUser', 'front/layout', compact('magazine', 'user', 'error'));
            exit();
        }
        header('location: index.php');
    }

    public function modifPass()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = 'Une erreur est survenue, veuillez recommencer vos modifications';
            if($this->request->post('passwordOld') !== null && !empty($this->request->post('passwordOld'))
            && password_verify($this->request->post('passwordOld'), $user->p_w))
            {
                if($this->request->post('passwordNew') !== null && !empty($this->request->post('passwordNew')))
                {
                    $error = 'Le nouveau mot de passe choisi n\'est pas valide';
                    if(preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('passwordNew')))
                    {
                        $error = 'Les deux mots de passe renseignés ne correspondent pas';
                        if($this->request->post('passwordNew') === $this->request->post('passwordNew2'))
                        {
                            $this->usersManager->modifPass((string) $user->id_user, (string) password_hash($this->request->post('passwordNew'), PASSWORD_DEFAULT));
                            $this->monCompte();
                            exit();
                        }
                    }
                }
            }
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/modifPassUser', 'front/layout', compact('magazine', 'user', 'error'));
            exit();
        }
        header('location: index.php');
    }

    public function modifPseudo()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = 'Une erreur est survenue, veuillez recommencer vos modifications';
            if($this->request->post('passwordOld') !== null && !empty($this->request->post('passwordOld'))
            && password_verify($this->request->post('passwordOld'), $user->p_w))
            {
                if($this->request->post('pseudoNew') !== null && !empty($this->request->post('pseudoNew')))
                {
                    $error = 'Le pseudo choisi n\'est pas valide';
                    if(strlen($this->request->post('pseudoNew')) > 3 && strlen($this->request->post('pseudoNew')) < 15)
                    {
                        $error = 'Le pseudo choisi est déjà utilisé';
                        $pseudoThere = $this->usersManager->pseudoUser( $this->request->post('pseudoNew'));
                        if( ($pseudoThere[0]) < 1)
                        {
                            $this->usersManager->modifPseudo((string) $user->id_user, $this->request->post('pseudoNew'));
                            $this->monCompte();
                            exit();
                        }
                    }
                }
            }
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/modifPseudoUser', 'front/layout', compact('magazine', 'user', 'error'));
            exit();
        }
        header('location: index.php');
    }

    public function modifEmail()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = 'Une erreur est survenue, veuillez recommencer vos modifications';
            if($this->request->post('passwordOld') !== null && !empty($this->request->post('passwordOld'))
            && password_verify($this->request->post('passwordOld'), $user->p_w))
            {
                if($this->request->post('mailNew') !== null && !empty($this->request->post('mailNew')))
                {
                    $error = 'Les emails renseignés ne correspondent pas';
                    if(($this->request->post('mailNew')) === ($this->request->post('mailNew2')))
                    {
                        $error = 'L\' email choisi est déjà utilisé';
                        $emailThere = $this->usersManager->emailUser( $this->request->post('mailNew'));
                        if( ($emailThere[0]) < 1)
                        {
                            $this->usersManager->modifEmail((string) $user->id_user, $this->request->post('mailNew'));
                            $this->monCompte();
                            exit();
                        }
                    }
                }
            }
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/modifEmailUser', 'front/layout', compact('magazine', 'user', 'error'));
            exit();
        }
        header('location: index.php');
    }

    public function newsLetterAbo()
    {
        $user = $this->auth->user();
        if($user->newsletter === '0')
        {
            $this->usersManager->newsletter((string) $user->id_user, '1');
            $this->monCompte();
            exit();
        }
        $this->usersManager->newsletter((string) $user->id_user, '0');
        $this->monCompte();
    }

}