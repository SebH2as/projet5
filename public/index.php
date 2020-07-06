<?php
require('../vendor/autoload.php');


use Projet5\Controller\MagController;
use Projet5\Controller\ArticleController;
use Projet5\Tools\Request;

session_start();

$request = new Request();

$actionMag = 
[
    'chronics',
    'essais',
    'fictions',
    'article',
    'monCompte',
    'connection',
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
    'nextMag'
    

];

$actionArticle =
[
    'addContent',
    'deleteArticle',
    'createNewArticle',
    'modifyArticle',
    'previewArticle',
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
}


$controller = new magController();
$controller->lastMagazine();

    