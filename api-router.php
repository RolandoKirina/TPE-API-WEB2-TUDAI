<?php
require_once './libs/Router.php';
require_once './app/controllers/api-chocoreviewcontroller.php';
require_once './app/controllers/authcontroller.php';

// create the router
$router = new Router();

// routing table
$router->addRoute('reviews', 'GET', 'Reviewcontroller', 'getreviews');
$router->addRoute('reviews/:ID', 'GET', 'Reviewcontroller', 'getreview');
$router->addRoute('reviews/:ID', 'DELETE', 'Reviewcontroller', 'deletereview');
$router->addRoute('reviews', 'POST', 'Reviewcontroller', 'addreview'); 

// get token
$router->addRoute("auth/token", 'GET', 'Authcontroller', 'gettoken');
// url not found
$router->setDefaultRoute('Reviewcontroller', 'pagenotfound');

// run the route
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);