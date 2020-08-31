<?php
require('../vendor/autoload.php');


use Projet5\Controller\MagController;
use Projet5\Controller\ArticleController;
use Projet5\Controller\UserController;
use Projet5\Tools\Request;

session_start();

$request = new Request();

$actionMag = 
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
    'previewLetters'
];

$actionArticle =
[
    'addContent',
    'deleteArticle',
    'createNewArticle',
    'modifyArticle',
    'previewArticle',
    'chroniques',
    'essais',
    'fictions',
    'article',
];

$actionUser =
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
    'invalidation'
];

if (($request->get('action')) !== null){
    $key = array_search($request->get('action'), $actionMag);
    $methode = $actionMag[$key]; 
    if ($methode === $request->get('action')){
        $controller = new magController();
        $controller->$methode();
        exit();
    }
    $key = array_search($request->get('action'), $actionArticle);
    $methode = $actionArticle[$key]; 
    if ($methode === $request->get('action')){
        $controller = new articleController();
        $controller->$methode();
        exit();
    }
    $key = array_search($request->get('action'), $actionUser);
    $methode = $actionUser[$key]; 
    if ($methode === $request->get('action')){
        $controller = new userController();
        $controller->$methode();
        exit();
    }
}


$controller = new magController();
$controller->lastMagazine();

    