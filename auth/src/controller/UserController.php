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

        if (isset($_POST)) {
            $user = new UserModel($_POST);
            $errors = $user->create();
            if (empty($errors)) {
                $router->render('index', ['data' => UserModel::all()]);
            } else {
                $router->render('signup', ['errors' => $errors]);
            }
        }
    }
}
