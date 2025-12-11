<?php

namespace Debian\Php\auth\src\model;

use PDO;
use Debian\Php\auth\src\data\DBConnection;


class UserModel
{
    private array $errors = [];
    private array $args;
    private PDO $pdo;

    public function __construct(array $args, ?PDO $pdo = null)
    {
        // Allow dependency injection for testing, otherwise use singleton
        $this->args = $args;
        $this->pdo = $pdo ?? self::connectDB()->getConnection();
    }

    public static function connectDB(): DBConnection
    {
        return DBConnection::getInstance();
    }

    public static function all(): array
    {
        $pdo = self::connectDB()->getConnection();
        $res = $pdo->query("SELECT * FROM users");
        $data = $res->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function getEmail(): string
    {
        return $this->args['email'] ?? '';
    }

    public function getPassword(): string
    {
        return $this->args['password'] ?? '';
    }

    public function getFullName(): string
    {
        return $this->args['full_name'] ?? '';
    }

    public static function findByEmail($email)
    {
        $pdo = self::connectDB()->getConnection();
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute([$email]);
        $data = $statement->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function validate(): bool
    {
        $this->errors = [];

        if (empty($this->args['email'])) {
            $this->errors[] = 'Email is required';
        } elseif (!filter_var($this->args['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Invalid email format';
        }

        if (empty($this->args['password'])) {
            $this->errors[] = 'Password is required';
        } elseif (strlen($this->args['password']) < 6) {
            $this->errors[] = 'Password must be at least 6 characters';
        }

        if (empty($this->args['full_name'])) {
            $this->errors[] = 'Full name is required';
        }

        return empty($this->errors);
    }

    public function create(): array
    {
        // Validate first
        if (!$this->validate()) {
            return $this->errors;
        }

        // Check if user already exists
        $existing = self::findByEmail($this->args['email']);
        if (!empty($existing)) {
            $this->errors[] = 'User already exists with this email';
            return $this->errors;
        }

        // Insert user
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (full_name, email, password_hash, role, created_at) 
                 VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $this->args['full_name'],
                $this->args['email'],
                password_hash($this->args['password'], PASSWORD_BCRYPT),
                $this->args['role'] ?? 'User',
                date('Y-m-d H:i:s')
            ]);

            return [];
        } catch (\PDOException $e) {
            $this->errors[] = 'Database error: ' . $e->getMessage();
            return $this->errors;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }



    public function read(): void {}
    public function update(): void {}
    public function delete(): void {}
}
