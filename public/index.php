<?php
require('../vendor/autoload.php');


use Projet5\Controller\MagController;
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
];

$actionBack =
[
    'admConnect',
    'episodes',
    'createEpisode',
    'reset',
    'profil',
    'commentDelete',
    'comDelete',
    'deleteR',
    'deleteReportsFromEp',
    'comPage',
    'modifyEpisode',
    'addEpisode',
    'episodeModications',
    'disconnection'
];

if (($request->get('action')) !== null){
    $key = array_search($request->get('action'), $actionMag);
    $methode = $actionMag[$key]; 
    if ($methode === $request->get('action')){
        $controller = new MagController();
        $controller->$methode();
        exit();
    }
    $key = array_search($request->get('action'), $actionBack);
    $methode = $actionBack[$key]; 
    if ($methode === $request->get('action')){
        $controller = new BackController();
        $controller->$methode();
        exit();
    }
}


$controller = new MagController();
$controller->magazine();

    