<?php
require_once './libs/Router.php';
require_once './app/controllers/api-chocolatecontroller.php';

// crea el router
$router = new Router();

// defina la tabla de ruteo
$router->addRoute('chocolates', 'GET', 'Chocolatecontroller', 'getchocolates');
   // $router->addRoute('chocolates/:ID', 'GET', 'TaskApiController', 'getTask');
   // $router->addRoute('chocolates/:ID', 'DELETE', 'TaskApiController', 'deleteTask');
   //$router->addRoute('chocolates', 'POST', 'TaskApiController', 'insertTask'); 

// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);