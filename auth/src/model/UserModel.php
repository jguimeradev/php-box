<?php

namespace Debian\Php\auth\src\model;

use PDO;
use Debian\Php\auth\src\data\DBConnection;


class UserModel
{
    public $db;
    public function __construct(public array $args)
    {
        $pdo = $this->connectDB()->getConnection();
    }

    public static function connectDB(): DBConnection
    {
        return $db = DBConnection::getInstance();
    }

    public static function all(): array
    {
        $pdo = self::connectDB()->getConnection();
        $res = $pdo->query("SELECT * FROM users");
        $data = $res->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function create(): void {}
    public function read(): void {}
    public function update(): void {}
    public function delete(): void {}
}
