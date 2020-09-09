<?php
declare(strict_types=1);

namespace Projet5\Controller;


use Projet5\View\View;
use Projet5\Controller\MagController;
use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\Model\UsersManager;
use Projet5\Model\LettersManager;
use Projet5\Tools\Request;
use Projet5\Tools\DataLoader;
use Projet5\Tools\Files;
use Projet5\Tools\Session;
use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;


class UserController{
        
    private $magManager;
    private $magController;
    private $articleManager;
    private $usersManager;
    private $lettersManager;
    private $view;
    private $request;
    private $dataLoader;
    private $files;
    private $session;
    private $auth;
    private $noCsrf;
    

    public function __construct()
    {
        $this->view = new View();
        $this->magController = new magController();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->usersManager = new usersManager();
        $this->lettersManager = new lettersManager();
        $this->request = new request();
        $this->dataLoader = new dataLoader();
        $this->files = new files();
        $this->session = new session();
        $this->auth = new auth();
        $this->noCsrf = new noCsrf();
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
        //$this->auth->requireRole('0');//
        if($user)
        {
            if($user->role === '0')
            {
                $nbUnpubletters = $this->lettersManager->countUnpubLetters($user->pseudo);
                $nbPubletters = $this->lettersManager->countPubLetters($user->pseudo);
                $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
                $this->view->render('front/monCompte', 'front/layout', compact('magazine', 'user', 'message', 'nbUnpubletters', 'nbPubletters'));
                exit();
            }
            if($user->role === '1')
            {
                $this->adminProfil();
                exit();
            }
            
        }
        header('location: index.php');
        
    }

    public function nousRejoindre():void//méthode pour afficher la page nous rejoindre
    {
        $error = null;
        $token = $this->noCsrf->createToken();
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $this->view->render('front/nousRejoindre', 'front/layout', compact('magazine', 'error', 'token'));
    }

    public function addUser():void
    {
        $isError = true;
        
        $error = 'Au moins un des champs est vide';
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
        {
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
                                        $token = $this->noCsrf->createToken();
                                        $this->view->render('front/activation', 'front/layout', compact('magazine', 'error', 'token'));
                                        exit();
                                    }
                                    
                                }
                            }
                            
                        }
                    }
                }  
            }
        }
        
        if($isError)
        {
            $token = $this->noCsrf->createToken();
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $this->view->render('front/nousRejoindre', 'front/layout', compact('magazine', 'error', 'token'));
        }
        
    }

    public function activation()
    {
        $isError = true;
        
        $error = 'Veuillez renseigner tous les champs';
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
        {
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
                    $this->connectionPage();
                    exit();
                }
            }
        }
        

        if($isError)
        {
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $token = $this->noCsrf->createToken();
            $this->view->render('front/activation', 'front/layout', compact('magazine', 'error', 'token'));
        }
    }

    public function connectionPage():void //méthode pour afficher la page de connection
    {
        $error = null;
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $token = $this->noCsrf->createToken();
        $this->view->render('front/connectionPage', 'front/layout', compact('magazine', 'error', 'token'));
        
    }

    public function connection():void //méthode pour se connecter au compte utilisateur ou au backoffice
    {
        $error = 'Pseudo ou mot de passe incorrect';
        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
        {
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
        }
        
        $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
        $token = $this->noCsrf->createToken();
        $this->view->render('front/connectionPage', 'front/layout', compact('magazine', 'error', 'token'));
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
            $token = $this->noCsrf->createToken();
            $this->view->render('front/nousEcrire', 'front/layout', compact('magazine', 'user', 'error', 'token'));
            exit();
        }
        header('location: index.php');
    }

    public function postLetter()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = 'Une erreur est survenue, veuillez soummettre votre courrier de nouveau';
            if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
            {
                if($this->request->post('courrier') !== null &&  !empty($this->request->post('courrier')))
                {
                    $this->lettersManager->postLetter($user->id_user, $user->pseudo, $this->request->post('courrier'));
                    $this->monCompte();
                    exit();
                }
            }
            
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $token = $this->noCsrf->createToken();
            $this->view->render('front/nousEcrire', 'front/layout', compact('magazine', 'user', 'error', 'token'));
            exit();
        }
    }

    public function modifPseudoUser()
    {
        $user = $this->auth->user();
        if($user)
        {
            $error = null;
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $token = $this->noCsrf->createToken();
            $this->view->render('front/modifPseudoUser', 'front/layout', compact('magazine', 'user', 'error', 'token'));
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
            $token = $this->noCsrf->createToken();
            $this->view->render('front/modifPassUser', 'front/layout', compact('magazine', 'user', 'error', 'token'));
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
            $token = $this->noCsrf->createToken();
            $this->view->render('front/modifEmailUser', 'front/layout', compact('magazine', 'user', 'error', 'token'));
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
            if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
            {
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
            }
            
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $token = $this->noCsrf->createToken();
            $this->view->render('front/modifPassUser', 'front/layout', compact('magazine', 'user', 'error', 'token'));
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
            if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
            {
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
            }
            
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $token = $this->noCsrf->createToken();
            $this->view->render('front/modifPseudoUser', 'front/layout', compact('magazine', 'user', 'error', 'token'));
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
            if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
            {
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
            }
            
            $magazine = $this->magManager->findOnlineMagWithArticles((int) $this->request->get('idMag'));
            $token = $this->noCsrf->createToken();
            $this->view->render('front/modifEmailUser', 'front/layout', compact('magazine', 'user', 'error', 'token'));
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

    public function courrier()
    {
        $this->auth->requireRole('1');
        $totalLetters = $this->lettersManager->countletters();
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


        $letters = $this->lettersManager->getAllLetters((int) $offset, (int) $nbByPage);
        $this->view->render('back/courrier', 'back/layout', compact('totalLetters', 'nbByPage', 'offset', 'currentpage', 'totalpages', 'letters'));
    }

    public function userLetter()
    {
        $this->auth->requireRole('1');
        $token = $this->noCsrf->createToken();
        $message = null;
        $numberMags = $this->magManager->getAllNumberMag();
        $letter = $this->lettersManager->getLetterById((int)$this->request->get('idLetter'));
        $this->view->render('back/userLetter', 'back/layout', compact('letter', 'numberMags', 'message', 'token'));
    }

    public function relatedMag()
    {
        $this->auth->requireRole('1');
        $this->lettersManager->addRelatedMag((int)$this->request->get('idLetter'), (int) $this->request->post('numberMag'));
        $message = 'Le magazine associé a été modifié';
        $numberMags = $this->magManager->getAllNumberMag();
        $letter = $this->lettersManager->getLetterById((int)$this->request->get('idLetter'));
        $token = $this->noCsrf->createToken();
        $this->view->render('back/userLetter', 'back/layout', compact('letter', 'numberMags', 'message', 'token'));
    }

    public function addResponse()
    {
        $this->auth->requireRole('1');
        $this->lettersManager->response((int)$this->request->get('idLetter'), (string) $this->request->post('contentResponse'));
        $message = 'La réponse a été enregistrée';
        $numberMags = $this->magManager->getAllNumberPubliMag();
        $letter = $this->lettersManager->getLetterById((int)$this->request->get('idLetter'));
        $token = $this->noCsrf->createToken();
        $this->view->render('back/userLetter', 'back/layout', compact('letter', 'numberMags', 'message', 'token'));
    }

    public function validation()
    {
        $this->auth->requireRole('1');
        $letter = $this->lettersManager->getLetterById((int)$this->request->get('idLetter'));
        if (($letter[0]->magRelated) !== null)
        {
            $this->lettersManager->validatedCourrier((int)$this->request->get('idLetter'));
            $message = 'Le courrier est validé et intégré au magazine associé';
            $numberMags = $this->magManager->getAllNumberPubliMag();
            $letter = $this->lettersManager->getLetterById((int)$this->request->get('idLetter'));
            $this->view->render('back/userLetter', 'back/layout', compact('letter', 'numberMags', 'message'));
        }
        $message = 'Le courrier doit être associé à un magazine avant sa validation';
        $numberMags = $this->magManager->getAllNumberPubliMag();
        $letter = $this->lettersManager->getLetterById((int)$this->request->get('idLetter'));
        $this->view->render('back/userLetter', 'back/layout', compact('letter', 'numberMags', 'message'));
        
    }

    public function invalidation()
    {
        $this->auth->requireRole('1');
        $this->lettersManager->invalidatedCourrier((int)$this->request->get('idLetter'));
        $message = 'Le courrier est invalidé et retiré du magazine associé';
        $numberMags = $this->magManager->getAllNumberPubliMag();
        $letter = $this->lettersManager->getLetterById((int)$this->request->get('idLetter'));
        $this->view->render('back/userLetter', 'back/layout', compact('letter', 'numberMags', 'message'));
    }

    public function courrierDelete()
    {
        $this->auth->requireRole('1');
        $this->lettersManager->deleteCourrier((int)$this->request->get('idLetter'));
        $this->courrier();
    }

    public function adminProfil()
    {
        $this->auth->requireRole('1');
        $message = null;
        $error = null;
        $token = $this->noCsrf->createToken();

        $this->view->render('back/admin', 'back/layout', compact('message', 'error', 'token'));
    }

    function reset():void //méthode pour réinitialiser les informations de l'administrateur
    {

        $message = null;
        $error = null;
        
        $this->auth->requireRole('1');

        if ($this->request->post('csrf') !== null && $this->noCsrf->isTokenValid($this->request->post('csrf')))
        {
            if($this->request->post('resetPseudo') !== null)
            {
                $user = $this->auth->user();
                if($user)
                {
                    $error = 'Une erreur est survenue, veuillez recommencer vos modifications';
                    if($this->request->post('pass01') !== null && !empty($this->request->post('pass01'))
                    && password_verify($this->request->post('pass01'), $user->p_w))
                    {
                        if($this->request->post('pseudo') !== null && !empty($this->request->post('pseudo')))
                        {
                            $error = 'Le pseudo choisi n\'est pas valide';
                            if(strlen($this->request->post('pseudo')) > 3 && strlen($this->request->post('pseudo')) < 15)
                            {
                                $error = 'Le pseudo choisi est déjà utilisé';
                                $pseudoThere = $this->usersManager->pseudoUser( $this->request->post('pseudo'));
                                if( ($pseudoThere[0]) < 1)
                                {
                                    $this->usersManager->modifPseudo((string) $user->id_user, $this->request->post('pseudo'));
                                    $message='Votre pseudo a été modifié';
                                    $error= null;
                                    $token = $this->noCsrf->createToken();
                                    $this->view->render('back/admin', 'back/layout', compact('message', 'error', 'token'));
                                    exit();
                                }
                            }
                        }
                    }
                    $this->view->render('back/admin', 'back/layout', compact('message', 'error'));
                    exit();
                }
                header('location: index.php');
            }

            if($this->request->post('resetEmail') !== null)
            {
                $user = $this->auth->user();
                if($user)
                {
                    $error = 'Une erreur est survenue, veuillez recommencer vos modifications';
                    if($this->request->post('pass02') !== null && !empty($this->request->post('pass02'))
                    && password_verify($this->request->post('pass02'), $user->p_w))
                    {
                        if($this->request->post('email') !== null && !empty($this->request->post('email')))
                        {
                            $error = 'Les emails renseignés ne correspondent pas';
                            if(($this->request->post('email')) === ($this->request->post('email02')))
                            {
                                $error = 'L\' email choisi est déjà utilisé';
                                $emailThere = $this->usersManager->emailUser( $this->request->post('email'));
                                if( ($emailThere[0]) < 1)
                                {
                                    $this->usersManager->modifEmail((string) $user->id_user, $this->request->post('email'));
                                    $message='Votre email a été modifié';
                                    $error= null;
                                    $token = $this->noCsrf->createToken();
                                    $this->view->render('back/admin', 'back/layout', compact('message', 'error', 'token'));
                                    exit();
                                }
                            }
                        }
                    }
                    $this->view->render('back/admin', 'back/layout', compact('message', 'error'));
                    exit();
                }
                header('location: index.php');
            }
            
            if($this->request->post('resetPass') !== null)
            {
                $user = $this->auth->user();
                if($user)
                {
                    $error = 'Une erreur est survenue, veuillez recommencer vos modifications';
                    if($this->request->post('pass03') !== null && !empty($this->request->post('pass03'))
                    && password_verify($this->request->post('pass03'), $user->p_w))
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
                                    $message='Votre mot de passe a été modifié';
                                    $error= null;
                                    $token = $this->noCsrf->createToken();
                                    $this->view->render('back/admin', 'back/layout', compact('message', 'error', 'token'));
                                    exit();
                                }
                            }
                        }
                    }
                    $this->view->render('back/admin', 'back/layout', compact('message', 'error'));
                    exit();
                }
                header('location: index.php');
            }
        }
    }

    public function usersAdmin()
    {
        $this->auth->requireRole('1');
        $totalUsers = $this->usersManager->countUsers();
        $nbByPage = 5;
        $totalpages = (int) ceil($totalUsers[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;


        $users = $this->usersManager->getAllUsers((int) $offset, (int) $nbByPage);
        $this->view->render('back/usersAdmin', 'back/layout', compact('totalUsers', 'nbByPage', 'offset', 'currentpage', 'totalpages', 'users'));
    }

    public function deleteUser()
    {
        $this->auth->requireRole('1');
        $this->usersManager->deleteUserById((int) $this->request->get('iduser'));
        $this->usersAdmin();
    }

}