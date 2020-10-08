<?php

namespace Projet5\Tools;

use Projet5\Model\Manager\MagManager;
use Projet5\Controller\MagController;
use Projet5\Controller\ArticleController;
use Projet5\Controller\UserController;
use Projet5\Tools\Request;
use Projet5\View\View;

final class Router
{
    private Database $database;
    private View $view;
    private Request $request;

    private $actionMag = 
    [
        'newMag',
        'createNewMag',
        'pannelMag',
        'listMag',
        'modifyMag',
        'previewMag',
        'addEdito',
        'deleteMag',
        'setOnlineMag',
        'setSavedMag',
        'previousMag',
        'nextMag',
        'magazine',
        'readersLetters',
        'previewLetters',
        'previewEdito',
        'editorial',
        'whoWeAre'
    ];

    private $actionArticle =
    [
        'addContent',
        'deleteArticle',
        'createNewArticle',
        'pannelArticle',
        'modifyArticle',
        'previewArticle',
        'chroniques',
        'essais',
        'fictions',
        'article',
        'unsetMain',
        'setMain'
    ];

    private $actionUser =
    [
        'monCompte',
        'nousRejoindre',
        'connectionPage',
        'addUser',
        'activation',
        'connection',
        'userDeco',
        'nousEcrire',
        'modifInfosUser',
        'modifPassUser',
        'modifEmailUser',
        'modifPseudoUser',
        'modifPass',
        'modifEmail',
        'modifPseudo',
        'postLetter',
        'newsLetterAbo',
        'courrier',
        'userLetter',
        'relatedMag',
        'addResponse',
        'validation',
        'courrierDelete',
        'invalidation',
        'adminProfil',
        'reset',
        'usersAdmin',
        'deleteUser'
    ];

    public function __construct()
    {
        $this->database = new Database();
        $this->view = new View();
        $this->request = new request();
    }

    public function run(): void
    {
        if (($this->request->get('action')) !== null){
            $key = array_search($this->request->get('action'), $this->actionMag);
            $methode = $this->actionMag[$key]; 
            if ($methode === $this->request->get('action')){
                $controller = new magController();
                $controller->$methode();
                exit();
            }
            $key = array_search($this->request->get('action'), $this->actionArticle);
            $methode = $this->actionArticle[$key]; 
            if ($methode === $this->request->get('action')){
                $controller = new articleController();
                $controller->$methode();
                exit();
            }
            $key = array_search($this->request->get('action'), $this->actionUser);
            $methode = $this->actionUser[$key]; 
            if ($methode === $this->request->get('action')){
                $controller = new userController();
                $controller->$methode();
                exit();
            }
        }
        $controller = new magController();
        $controller->lastMagazine();
    }

}







    