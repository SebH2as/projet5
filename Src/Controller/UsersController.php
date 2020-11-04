<?php
declare(strict_types=1);

namespace Projet5\Controller;

use Projet5\Model\Manager\LettersManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Model\Manager\NewslettersManager;
use Projet5\Model\Manager\UsersManager;
use Projet5\Tools\Auth;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\View\View;

final class UsersController
{
    private MagManager $magManager;
    private LettersManager $lettersManager;
    private NewslettersManager $newslettersManager;
    private UsersManager $usersManager;
    private View $view;
    private Request $request;
    private NoCsrf $noCsrf;
    private Auth $auth;

    public function __construct(UsersManager $usersManager, MagManager $magManager, LettersManager $lettersManager, NewslettersManager $newslettersManager, View $view, Request $request, NoCsrf $noCsrf, Auth $auth)
    {
        $this->magManager = $magManager;
        $this->lettersManager = $lettersManager;
        $this->newslettersManager = $newslettersManager;
        $this->view = $view;
        $this->usersManager = $usersManager;
        $this->request = $request;
        $this->auth = $auth;
        $this->noCsrf = $noCsrf;
    }

    //index.php?action=nousRejoindre&idMag=122
    public function nousRejoindre(int $idMag):void//méthode pour afficher la page nous rejoindre
    {
        $error = null;
        if ($this->request->get('error') !== null) {
            $error = $this->request->get('error');
        }
        
        $token = $this->noCsrf->createToken();
        $magazine = $this->magManager->showByIdAndPub($idMag);

        $this->view->render(
            [
            'template' => 'front/nousRejoindre',
            'data' => [
                'magazine' => $magazine,
                'error' => $error,
                'preview' => 0,
                'active' => 0,
                'token' => $token,
                ],
            ],
        );
    }

    //index.php?action=connectionPage&idMag=122
    public function connectionPage(int $idMag):void //méthode pour afficher la page de connection
    {
        $error = null;
        if ($this->request->get('error')) {
            $error = 'Pseudo ou mot de passe incorrect';
        }

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = $this->request->get('message');
        }
        
        $token = $this->noCsrf->createToken();
        $magazine = $this->magManager->showByIdAndPub($idMag);
        
        $this->view->render(
            [
            'template' => 'front/connectionPage',
            'data' => [
                'magazine' => $magazine,
                'error' => $error,
                'preview' => 0,
                'active' => 0,
                'token' => $token,
                'message' => $message,
                ],
            ],
        );
    }

    //index.php?action=connection&idMag=122
    public function connection(int $idMag):void //méthode pour se connecter au compte utilisateur ou au backoffice
    {
        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            header("Location: index.php?action=connectionPage&idMag=$idMag&error=true");
            exit();
        }

        if ($this->request->post('pseudo') === null ||  empty($this->request->post('pseudo'))
        || $this->request->post('password') === null ||  empty($this->request->post('password'))) {
            header("Location: index.php?action=connectionPage&idMag=$idMag&error=true");
            exit();
        }
        
        $user = $this->auth->login($this->request->post('pseudo'), $this->request->post('password'));

        if ($user === null) {
            header("Location: index.php?action=connectionPage&idMag=$idMag&error=true");
            exit();
        }

        if ($user->getRole() === 1) {
            header("Location: index.php?action=listMag&idMag=$idMag");
            exit();
        }

        header("Location: index.php?action=monCompte&idMag=$idMag");
        exit();
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
        'votre Email a bien été modifié',
        'Une erreur est survenue, veuillez recommencer'];
        
        if ($this->request->get('message') !== null) {
            $message = $messageContent[(int) $this->request->get('message')];
        }
        
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }
            
        if ($user->getRole() === 1) {
            header("Location: index.php?action=adminProfil&message=$message");
            exit();
        }
            
        $nbUnpubletters = $this->lettersManager->countUnpubById((int) $user->getId_user());
        $nbPubletters = $this->lettersManager->countPubById((int) $user->getId_user());
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
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }
        
        session_unset();
        session_destroy();
        session_write_close();
        header('location: index.php');
    }

    public function userDeleteSelf(int $idMag): void
    {
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }

        $message = 'Votre compte a été supprimé';

        $this->usersManager->deleteUserById($user->getId_user());

        session_unset();
        session_destroy();
        session_write_close();

        header("Location: index.php?action=connectionPage&idMag=$idMag&message=$message");
        exit();
    }

    //index.php?action=newsLetterAbo&idMag=122
    public function newsletterAbo(int $idMag):void//méthode pour s'abonner ou se désabonner à la newsletter
    {
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }

        if ($user->getNewsletter() === 0) {
            $this->usersManager->getAboNewsletter((int) $user->getId_user(), 1);
            header("Location: index.php?action=monCompte&idMag=$idMag&message=2");
            exit();
        }

        $this->usersManager->abortAboNewsletter((int) $user->getId_user(), 0);
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
        
        $error = null;
        if ($this->request->get('error') !== null) {
            $error = $this->request->get('error');
        }
        
        $magazine = $this->magManager->showByIdAndPub($idMag);
        $token = $this->noCsrf->createToken();
        $userData = (string) $this->request->get('userData');
        
        $this->view->render(
            [
            'template' => 'front/modifUser',
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

    //index.php?action=modifDataUser&idMag=122
    public function modifDataUser(int $idMag):void//méthode pour modifier une de ses données en tant qu'utilisateur
    {
        $user = $this->auth->user();

        if ($user === null) {
            header('location: index.php');
            exit();
        }

        $userData = $this->request->get('userData');

        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            header("Location: index.php?action=monCompte&idMag=$idMag&message=6");
            exit();
        }

        if ($this->request->post('password') === null || empty($this->request->post('password')) ||
        $this->request->post('new') === null || empty($this->request->post('new')) ||
        $this->request->post('new2') === null || empty($this->request->post('new2'))) {
            $error = 'Au moins un des champs est vide. Veuillez tous les renseigner.';

            if ($user->getRole() === 1) {
                header("Location: index.php?action=modifAdmin&error=$error");
                exit();
            }
            
            header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
            exit();
        }

        if (password_verify($this->request->post('password'), $user->getP_w()) === false) {
            $error = 'Erreur de mot de passe';

            if ($user->getRole() === 1) {
                header("Location: index.php?action=modifAdmin&error=$error");
                exit();
            }
            
            header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
            exit();
        }

        if ($this->request->post('new') !== $this->request->post('new2')) {
            $error = 'Les deux champs renseignés ne correspondent pas';

            if ($user->getRole() === 1) {
                header("Location: index.php?action=modifAdmin&error=$error");
                exit();
            }
            
            header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
            exit();
        }

        switch ($userData) {
            case "Mot de passe":

                if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('new')) === 0) {
                    $error = 'Le nouveau mot de passe choisi n\'est pas valide';

                    if ($user->getRole() === 1) {
                        header("Location: index.php?action=modifAdmin&error=$error");
                        exit();
                    }
                    
                    header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
                    exit();
                }

                if (password_verify($this->request->post('new'), $user->getP_w()) === true) {
                    $error = 'Le nouveau mot de passe choisi doit être différent de l\'ancien';

                    if ($user->getRole() === 1) {
                        header("Location: index.php?action=modifAdmin&error=$error");
                        exit();
                    }
                    
                    header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
                    exit();
                }
        
                $this->usersManager->modifPass((int) $user->getId_user(), (string) password_hash($this->request->post('new'), PASSWORD_DEFAULT));
                
                header("Location: index.php?action=monCompte&idMag=$idMag&message=0");
                exit();
                
                break;

            case "Pseudo":

                if (preg_match("(^[a-z]{3,15}\d*$)", $this->request->post('new')) === 0) {
                    $error = 'Le nouveau pseudo choisi n\'est pas valide';

                    if ($user->getRole() === 1) {
                        header("Location: index.php?action=modifAdmin&error=$error");
                        exit();
                    }
                    
                    header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
                    exit();
                }

                $pseudoThere = $this->usersManager->countPseudoUser($this->request->post('new'));
                if (($pseudoThere[0]) >= 1) {
                    $error = 'Le pseudo choisi est déjà utilisé';

                    if ($user->getRole() === 1) {
                        header("Location: index.php?action=modifAdmin&error=$error");
                        exit();
                    }
                    
                    header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
                    exit();
                }

                $this->usersManager->modifPseudo((int) $user->getId_user(), (string) $this->request->post('new'));
                $this->lettersManager->changeLetterAuthor((int) $user->getId_user(), (string) $this->request->post('new'));

                header("Location: index.php?action=monCompte&idMag=$idMag&message=4");
                exit();

                break;

            case "Email":

                if (filter_var($this->request->post('new'), FILTER_VALIDATE_EMAIL) === false) {
                    $error = 'L\'email choisi n\'est pas valide';

                    if ($user->getRole() === 1) {
                        header("Location: index.php?action=modifAdmin&error=$error");
                        exit();
                    }
                    
                    header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
                    exit();
                }

                $emailThere = $this->usersManager->countEmailUser($this->request->post('new'));
                if (($emailThere[0]) >= 1) {
                    $error = 'L\' email choisi est déjà utilisé';

                    if ($user->getRole() === 1) {
                        header("Location: index.php?action=modifAdmin&error=$error");
                        exit();
                    }
                    
                    header("Location: index.php?action=modifUser&idMag=$idMag&userData=$userData&error=$error");
                    exit();
                }
                
                $this->usersManager->modifEmail((int) $user->getId_user(), (string) $this->request->post('new'));

                header("Location: index.php?action=monCompte&idMag=$idMag&message=5");
                exit();

                break;

            default:

                header("Location: index.php?action=monCompte&idMag=$idMag&message=6");
                exit();

                break;
        }
    }

    //index.php?action=addUser&idMag=122
    public function addUser(int $idMag):void//méthode pour devenir utilisateur enregistré du site
    {
        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $error = "Une erreur est survenue, veuillez recommencer";
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        if ((string) $this->request->post('pseudo') === null ||  empty($this->request->post('pseudo'))
        || (string) $this->request->post('mail') === null ||  empty($this->request->post('mail'))
        || (string) $this->request->post('mail2') === null ||  empty($this->request->post('mail2'))
        || (string) $this->request->post('password') === null ||  empty($this->request->post('password'))
        || (string) $this->request->post('password2') === null ||  empty($this->request->post('password2'))) {
            $error = 'Au moins un des champs est vide. Veuillez tous les renseigner.';
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        if (preg_match("(^[A-Za-z]{3,15}\d*$)", $this->request->post('pseudo')) === 0) {
            $error = 'Le pseudo choisi ne correspond aux critères définis dans la note d\'information du champ Pseudo';
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        $pseudoThere = $this->usersManager->countPseudoUser($this->request->post('pseudo'));
        if (($pseudoThere[0]) >= 1) {
            $error = 'Le pseudo choisi est déjà utilisé';
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        if (filter_var($this->request->post('mail'), FILTER_VALIDATE_EMAIL) === false) {
            $error = 'L\'email choisi n\'est pas valide';
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        $emailThere = $this->usersManager->countEmailUser($this->request->post('mail'));
        if (($emailThere[0]) >= 1) {
            $error = 'L\' email choisi est déjà utilisé';
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        if (($this->request->post('mail')) !== ($this->request->post('mail2'))) {
            $error = 'Les emails renseignés ne correspondent pas';

            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        if (($this->request->post('password')) !== ($this->request->post('password2'))) {
            $error = 'Les mots de passe renseignés dans les deux champs de saisi ne correspondent pas';

            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('password')) === 0) {
            $error = 'Le mot de passe choisi ne correspond aux critères définis dans la note d\'information du champ Mot de passe';
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        if ((string) $this->request->post('check') === null ||  empty($this->request->post('check'))) {
            $error = 'Vous devez accepter nos conditions d\'utilisation pour pouvoir créer votre compte';
            
            header("Location: index.php?action=nousRejoindre&idMag=$idMag&error=$error");
            exit();
        }

        $key = '';
        for ($i = 1; $i<6 ; $i++) {
            $key .= random_int(0, 9);
        }

        $Email = $this->request->post('mail');

        mail($Email, "Code de validation", $key);
        
        $this->usersManager->addUser((string) $this->request->post('pseudo'), (string) $this->request->post('mail'), (string) password_hash($this->request->post('password'), PASSWORD_DEFAULT), (int) $key);
        
        $magazine = $this->magManager->showByIdAndPub($idMag);
        $token = $this->noCsrf->createToken();

        $this->view->render(
            [
            'template' => 'front/activationPage',
            'data' => [
                'magazine' => $magazine,
                'preview' => 0,
                'active' =>0,
                'token' => $token,
                ],
            ],
        );
    }

    //index.php?action=activation&idMag=122
    public function activation(int $idMag):void//Méthode pour activer le compte utilisateur
    {
        if ($this->request->post('csrf') === null || $this->noCsrf->isTokenNotValid($this->request->post('csrf'))) {
            $error = "Une erreur est survenue, veuillez recommencer";

            header("Location: index.php?action=activation&idMag=$idMag&error=$error");
            exit();
        }

        if ($this->request->post('pseudo') === null ||  empty($this->request->post('pseudo'))
        || $this->request->post('code') === null ||  empty($this->request->post('code'))) {
            $error = 'Veuillez renseigner tous les champs';
            
            header("Location: index.php?action=activationPage&idMag=$idMag&error=$error");
            exit();
        }

        $user = $this->usersManager->getUserByPseudoNotActived($this->request->post('pseudo'));
        if ($user === null) {
            $error = 'Le pseudo est erronné ou le compte déjà activé';
            
            header("Location: index.php?action=activationPage&idMag=$idMag&error=$error");
            exit();
        }

        if (((int) $this->request->post('code')) !== $user->getConfirmkey()) {
            $error = 'Le code renseigné n\'est pas valide';
            
            header("Location: index.php?action=activationPage&idMag=$idMag&error=$error");
            exit();
        }
        
        $this->usersManager->activeAccountByPseudo((string) $this->request->post('pseudo'));

        $message = 'Votre compte a été activé. Vous pouvez maintenant vous connecter';

        header("Location: index.php?action=connectionPage&idMag=$idMag&message=$message");
        exit();
    }

    //index.php?action=activationPage&idMag=122
    public function activationPage(int $idMag): void//Méthode pour afficher la page d'activation du compte
    {
        $error = null;
        if ($this->request->get('error') !== null) {
            $error = $this->request->get('error');
        }

        $magazine = $this->magManager->showByIdAndPub($idMag);
        $token = $this->noCsrf->createToken();

        $this->view->render(
            [
            'template' => 'front/activationPage',
            'data' => [
                'magazine' => $magazine,
                'preview' => 0,
                'active' =>0,
                'token' => $token,
                'error' =>$error,
                ],
            ],
        );
    }

    public function usersBack(int $idMag): void
    {
        $this->auth->requireRole(1);

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

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


        $users = $this->usersManager->showAllUsers((int) $offset, (int) $nbByPage);
        
        $this->view->render(
            [
            'template' => 'back/usersBack',
            'data' => [
                'users' => $users,
                'currentpage' => $currentpage,
                'totalpages' => $totalpages,
                'message' => $message,
                ],
            ],
        );
    }

    public function deleteUser(int $idMag): void
    {
        $this->auth->requireRole(1);

        $idUser = (int)$this->request->get('idUser');
        $user = $this->usersManager->getAllUserById($idUser);

        $message = 'Le membre '. $user->getPseudo() .' a été supprimé';

        $this->usersManager->deleteUserById($idUser);

        header("Location: index.php?action=usersBack&message=$message");
        exit();
    }

    public function adminProfil(int $idMag): void
    {
        $this->auth->requireRole(1);

        $token = $this->noCsrf->createToken();

        $message = null;
        if ($this->request->get('message') !== null) {
            $message = htmlspecialchars($this->request->get('message'));
        }

        $error = null;
        if ($this->request->get('error') !== null) {
            $error = htmlspecialchars($this->request->get('error'));
        }

        $this->view->render(
            [
            'template' => 'back/adminProfil',
            'data' => [
                'error' => $error,
                'message' => $message,
                'token' => $token,
                ],
            ],
        );
    }

    //index.php?action=modifUser&idMag=122&userData=Pseudo
    public function modifAdmin(int $idMag):void//méthode pour accéder à une page de modification d'une des données de l'utilisateur
    {
        $this->auth->requireRole(1);
        
        $error = null;
        if ($this->request->get('error') !== null) {
            $error = $this->request->get('error');
        }
        
        $token = $this->noCsrf->createToken();
        $userData = (string) $this->request->get('userData');
        
        $this->view->render(
            [
            'template' => 'back/modifAdmin',
            'data' => [
                'token' => $token,
                'error' => $error,
                'userData' => $userData,
                ],
            ],
        );
    }

    public function sendNewsletter(): void
    {
        $this->auth->requireRole(1);

        $idNewsletter = (int)$this->request->get('idNewsletter');

        $newsletter = $this->newslettersManager->showNewslettersById($idNewsletter);

        if ($newsletter->getContent() === null || empty($newsletter->getContent())) {
            $error = 'La newsletter ne contient aucune ligne de texte et ne peut donc être envoyée';

            header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter&error=$error");
            exit();
        }
        
        $usersNb = $this->usersManager->countUsers();
        $users = $this->usersManager->showAllUsers(0, (int) $usersNb[0]);

        $header="MIME-Version: 1.0\r\n";
        $header.='Content-Type:text/html; charset="uft-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';

        $message = html_entity_decode((htmlspecialchars_decode($newsletter->getContent())));
        
        if ($users === null) {
            $error = 'Aucun membre abonné, la newsletter ne peut être envoyée';

            header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter&error=$error");
            exit();
        }

        for ($i = 0; $i < count($users); ++$i) {
            if ($users[$i]->getNewsletter() === '1') {
               
                mail($users[$i]->getEmail(), "Newsletter", $message, $header);
            }
        }
        
        $message = 'La newsletter a été envoyée aux membres abonnés';

        $this->newslettersManager->setNewsLetterSendById($idNewsletter, 1);

        header("Location: index.php?action=newsletterBack&idNewsletter=$idNewsletter&message=$message");
        exit();
    }
}
