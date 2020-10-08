<?php
require('../vendor/autoload.php');

use Projet5\Tools\Router;

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

session_start();

$router = new Router();
$router->run();