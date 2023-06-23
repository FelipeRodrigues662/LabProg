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
        
        // Verificar se o usuário já existe no banco de dados
        $existingUser = User::getUserByEmail($this->email);
        
        if ($existingUser) {
            // Atualizar os dados do usuário
            if (empty($this->password)) {
                // Manter a senha existente
                $stmt = $conn->prepare("UPDATE users SET name = ?, user_type = ? WHERE email = ?");
                $stmt->bind_param("sss", $this->name, $this->userType, $this->email);
            } else {
                // Atualizar a senha
                $stmt = $conn->prepare("UPDATE users SET name = ?, password = ?, user_type = ? WHERE email = ?");
                $stmt->bind_param("ssss", $this->name, $this->password, $this->userType, $this->email);
            }
        } else {
            // Inserir um novo usuário
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $this->name, $this->email, $this->password, $this->userType);
        }
        
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

    public static function getUsers($search = null) {
        $conn = getConnection(); // Supondo que a função getConnection() esteja definida dentro da classe User
        $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ?");
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = new User($row['id'], $row['name'], $row['email'], $row['password'], $row['user_type']);
            $users[] = $user;
        }
        $stmt->close();
        $conn->close();
        return $users;
    }
    public static function deleteById($id)
    {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }


}
