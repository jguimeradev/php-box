<?php

namespace Debian\Php\auth\src\model;

use Debian\Php\auth\src\data\DBConnection;


class UserModel
{
    public $db;
    public function __construct(public array $args)
    {
        $pdo = $this->connectDB()->getConnection();
    }

    public function connectDB(): DBConnection
    {
        return $db = DBConnection::getInstance();
    }

    public function all()
    {
        $pdo = $this->connectDB()->getConnection();
        $pdo->exec("SELECT * FROM users");
    }

    public function create(): void {}
    public function read(): void {}
    public function update(): void {}
    public function delete(): void {}
}
