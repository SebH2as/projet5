<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Letter;
use Projet5\Model\Entity\User;
use Projet5\Model\Repository\LettersRepository;
use Projet5\Model\Repository\UsersRepository;

use Projet5\Tools\Request;
use Projet5\Tools\Session;

final class UsersManager
{
    private UsersRepository $usersRepo;
    private LettersRepository $lettersRepo;
    private Request $request;
    private Session $session;

    public function __construct(UsersRepository $usersRepository, LettersRepository $lettersRepository, Session $session, Request $request)
    {
        $this->usersRepo = $usersRepository;
        $this->lettersRepo = $lettersRepository;
        $this->session = $session;
        $this->request = $request;
    }

    public function getUserByPseudo(string $pseudo): ?User
    {
        return $this->usersRepo->findUserByPseudo($pseudo);
    }

    public function getUserByPseudoNotActived(string $pseudo): ?User
    {
        return $this->usersRepo->findUserByPseudoNotActived($pseudo);
    }

    public function getUserById(int $userId): ?User
    {
        return $this->usersRepo->findUserById($userId);
    }

    public function getAllUserById(int $userId): ?User
    {
        return $this->usersRepo->findAllUserById($userId);
    }

    public function activeAccountByPseudo(string $pseudo): bool
    {
        if ($this->request->post('pseudo') === null ||  empty($this->request->post('pseudo'))
        || $this->request->post('code') === null ||  empty($this->request->post('code'))) {
            $this->session->setSessionData('error', 'Veuillez renseigner tous les champs');
            return false;
        }

        $user = $this->usersRepo->findUserByPseudoNotActived($this->request->post('pseudo'));
        if ($user === null) {
            $this->session->setSessionData('error', 'Le pseudo est erronné ou le compte déjà activé');
            return false;
        }

        if (((int) $this->request->post('code')) !== $user->getConfirmkey()) {
            $this->session->setSessionData('error', 'Le code renseigné n\'est pas valide');
            return false;
        }

        $user = new User();
        $user->setPseudo((string) $this->request->post('pseudo'));
        $user->setActived(1);

        $return = $this->usersRepo->activeAccountByPseudo($user);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function countUsers(): ?array
    {
        return $this->usersRepo->countUsers();
    }

    public function showAllUsers(int $offset, int $nbByPage): ?array
    {
        return $this->usersRepo->findAllUsers($offset, $nbByPage);
    }

    public function deleteUserById(int $idUser): bool
    {
        $user = new User();
        $user->setId_user($idUser);
        
        $this->usersRepo->deleteUserById($user);

        return true;
    }

    public function showAllAboUsers(): void
    {
        $this->usersRepo->findAllAboUsers();
    }

    public function newUser(): bool
    {
        if ($this->request->post('pseudo') === null ||  empty($this->request->post('pseudo'))
        || $this->request->post('mail') === null ||  empty($this->request->post('mail'))
        || $this->request->post('mail2') === null ||  empty($this->request->post('mail2'))
        || $this->request->post('password') === null ||  empty($this->request->post('password'))
        || $this->request->post('password2') === null ||  empty($this->request->post('password2'))) {
            $this->session->setSessionData('error', 'Au moins un des champs est vide. Veuillez tous les renseigner');
            return false;
        }

        if (preg_match("(^[A-Za-z]{3,15}\d*$)", $this->request->post('pseudo')) === 0) {
            $this->session->setSessionData('error', 'Le pseudo choisi ne correspond aux critères définis dans la note d\'information du champ Pseudo');
            return false;
        }

        $pseudoThere = $this->usersRepo->countPseudoUser($this->request->post('pseudo'));
        if (($pseudoThere[0]) >= 1) {
            $this->session->setSessionData('error', 'Le pseudo choisi est déjà utilisé');
            return false;
        }

        if (filter_var($this->request->post('mail'), FILTER_VALIDATE_EMAIL) === false) {
            $this->session->setSessionData('error', 'L\'email choisi n\'est pas valide');
            return false;
        }

        $emailThere = $this->usersRepo->countEmailUser($this->request->post('mail'));
        if (($emailThere[0]) >= 1) {
            $this->session->setSessionData('error', 'L\' email choisi est déjà utilisé');
            return false;
        }

        if (($this->request->post('mail')) !== ($this->request->post('mail2'))) {
            $this->session->setSessionData('error', 'Les emails renseignés ne correspondent pas');
            return false;
        }

        if (($this->request->post('password')) !== ($this->request->post('password2'))) {
            $this->session->setSessionData('error', 'Les mots de passe renseignés dans les deux champs de saisi ne correspondent pas');
            return false;
        }

        if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('password')) === 0) {
            $this->session->setSessionData('error', 'Le mot de passe choisi ne correspond aux critères définis dans la note d\'information du champ Mot de passe');
            return false;
        }

        if ($this->request->post('check') === null ||  empty($this->request->post('check'))) {
            $this->session->setSessionData('error', 'Vous devez accepter nos conditions d\'utilisation pour pouvoir créer votre compte');
            return false;
        }

        $key = '';
        for ($i = 1; $i<6 ; $i++) {
            $key .= random_int(0, 9);
        }

        //$Email = $this->request->post('mail');

        //mail($Email, "Code de validation", $key);

        $user = new User();
        $user->setPseudo((string) $this->request->post('pseudo'));
        $user->setEmail((string) $this->request->post('mail'));
        $user->setP_w((string) password_hash($this->request->post('password'), PASSWORD_DEFAULT));
        $user->setConfirmKey((int) $key);

        $return = $this->usersRepo->addUser($user);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function updateData(string $userData): bool
    {
        $user = $this->usersRepo->findUserById($this->session->getSessionData('userId'));

        if ($this->request->post('password') === null || empty($this->request->post('password')) ||
        $this->request->post('new') === null || empty($this->request->post('new')) ||
        $this->request->post('new2') === null || empty($this->request->post('new2'))) {
            $this->session->setSessionData('error', 'Au moins un des champs est vide. Veuillez tous les renseigner');
            return false;
        }

        if (password_verify($this->request->post('password'), $user->getP_w()) === false) {
            $this->session->setSessionData('error', 'Erreur de mot de passe');
            return false;
        }

        if ($this->request->post('new') !== $this->request->post('new2')) {
            $this->session->setSessionData('error', 'Les deux champs renseignés ne correspondent pas');
            return false;
        }

        switch ($userData) {
            case "Mot de passe":

                if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('new')) === 0) {
                    $this->session->setSessionData('error', 'Le nouveau mot de passe choisi n\'est pas valide');
                    return false;
                }

                if (password_verify($this->request->post('new'), $user->getP_w()) === true) {
                    $this->session->setSessionData('error', 'Le nouveau mot de passe choisi doit être différent de l\'ancien');
                    return false;
                }

                $userUpdate = new User();
                $userUpdate->setId_user((int) $user->getId_user());
                $userUpdate->setP_w((string) password_hash($this->request->post('new'), PASSWORD_DEFAULT));
                
                $return = $this->usersRepo->modifPass($userUpdate);
                $this->session->setSessionData('message', '0');
                
                if (!$return) {
                    $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
                }
        
                return $return;

            case "Pseudo":

                if (preg_match("(^[a-z]{3,15}\d*$)", $this->request->post('new')) === 0) {
                    $this->session->setSessionData('error', 'Le nouveau pseudo choisi n\'est pas valide');
                    return false;
                }

                $pseudoThere = $this->usersRepo->countPseudoUser($this->request->post('new'));
                if (($pseudoThere[0]) >= 1) {
                    $this->session->setSessionData('error', 'Le pseudo choisi est déjà utilisé');
                    return false;
                }

                
                $letter = new Letter();
                $letter->setId_user((int) $user->getId_user());
                $letter->setAuthor((string) $this->request->post('new'));
                $this->lettersRepo->changeLetterAuthor($letter);

                $userUpdate = new User();
                $userUpdate->setId_user((int) $user->getId_user());
                $userUpdate->setPseudo((string) $this->request->post('new'));
                $return = $this->usersRepo->modifPseudo($userUpdate);

                $this->session->setSessionData('message', '4');

                if (!$return) {
                    $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
                }
        
                return $return;


            case "Email":

                if (filter_var($this->request->post('new'), FILTER_VALIDATE_EMAIL) === false) {
                    $this->session->setSessionData('error', 'L\'email choisi n\'est pas valide');
                    return false;
                }

                $emailThere = $this->usersRepo->countEmailUser($this->request->post('new'));
                if (($emailThere[0]) >= 1) {
                    $this->session->setSessionData('error', 'L\' email choisi est déjà utilisé');
                    return false;
                }

                $userUpdate = new User();
                $userUpdate->setId_user((int) $user->getId_user());
                $userUpdate->setEmail((string) $this->request->post('new'));
                $return = $this->usersRepo->modifEmail($userUpdate);

                $this->session->setSessionData('message', '5');

                if (!$return) {
                    $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
                }
        
                return $return;


            default:

                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
                return false;

        }
    }

    public function updateAboNewsletter(): bool
    {
        $user = $this->usersRepo->findUserById($this->session->getSessionData('userId'));

        if ($user->getNewsletter() === 0) {
            $userUpdate = new User();
            $userUpdate->setId_user((int) $user->getId_user());
            $userUpdate->setNewsletter((int) 1);

            $return = $this->usersRepo->setAboNewsletter($userUpdate);

            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }
            $this->session->setSessionData('message', '2');
            return $return;
        }

        if ($user->getNewsletter() === 1) {
            $userUpdate = new User();
            $userUpdate->setId_user((int) $user->getId_user());
            $userUpdate->setNewsletter((int) 0);

            $return = $this->usersRepo->setAboNewsletter($userUpdate);

            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }
            $this->session->setSessionData('message', '3');
            return $return;
        }

        return false;
    }
}
