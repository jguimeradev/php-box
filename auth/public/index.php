<?php

require '../../vendor/autoload.php';

use Debian\Php\auth\src\http\Router;
use Debian\Php\auth\src\data\DBConnection;
use Debian\Php\auth\src\controller\UserController;

$db = DBConnection::getInstance();
$db->getConnection();


$router = new Router;

$router->get('/', [new UserController(), 'index']);
$router->get('/signup', fn() => $router->render('signup', null));
$router->post('/signup', [new UserController(), 'save']);
//$router->post('/signup', [new UserController(), 'save']); 


$router->dispatch();
