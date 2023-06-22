<?php

require_once __DIR__ . '/../database/connection.php';

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $userType;

    public function __construct($id ,$name, $email, $password, $userType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->userType = $userType;
    }

    public function getId() {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public function save()
    {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->name, $this->email, $this->password, $this->userType);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function authenticate($email, $password)
    {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        if ($user && password_verify($password, $user['password'])) {
            return new User($user['id'], $user['name'], $user['email'], $user['password'], $user['user_type']);
        } else {
            return null;
        }
    }

    public static function getUserByEmail($email)
    {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user ? new User($user['id'], $user['name'], $user['email'], $user['password'], $user['user_type']) : null;
    }

    public static function getById($id)
    {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user ? new User($user['id'], $user['name'], $user['email'], $user['password'], $user['user_type']) : null;
    }

}
