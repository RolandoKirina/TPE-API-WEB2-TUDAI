<?php
require_once './libs/Router.php';
require_once './app/controllers/api-chocoreviewcontroller.php';

// crea el router
$router = new Router();

// tabla de ruteo
$router->addRoute('reviews', 'GET', 'Reviewcontroller', 'getreviews');
$router->addRoute('reviews/:ID', 'GET', 'Reviewcontroller', 'getreview');
$router->addRoute('reviews/:ID', 'DELETE', 'Reviewcontroller', 'deletereview');
$router->addRoute('reviews', 'POST', 'Reviewcontroller', 'addreview'); 

//$router->addRoute('reviews', 'PUT', 'Reviewcontroller', 'editreview');

// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);