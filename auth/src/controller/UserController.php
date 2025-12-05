<?php

namespace Debian\Php\auth\src\controller;

use Debian\Php\auth\src\model\UserModel;
use Debian\Php\auth\src\http\Router;

class UserController
{
    public function index(Router $router)
    {
        $data = UserModel::all();
        $router->render('index', ['data' => $data]);
    }

    public function save(Router $router)
    {
        //el controlador solo maneja qué hacer una vez se realiza el save en el modelo. Es el modelo el que se encarga de la validación

        if (isset($_POST)) {
            $user = new UserModel($_POST);
            $res = $user->create();
            $router->render('index', null);
        }
    }
}
