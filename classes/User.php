<?php
require_once __DIR__ . '/../database/connection.php';

class User {
    private $id;
    private $name;
    private $email;
    private $password;
    private $userType;

    public function __construct($name, $email, $password, $userType) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->userType = $userType;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUserType() {
        return $this->userType;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setUserType($userType) {
        $this->userType = $userType;
    }

    // Database interaction methods
    public function save() {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->name, $this->email, $this->password, $this->userType);
        $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
    }

    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user ? new User($user['name'], $user['email'], $user['password'], $user['user_type']) : null;
    }

    // Authentication method
    public static function authenticate($email, $password) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        if ($user && password_verify($password, $user['password'])) {
            return new User($user['name'], $user['email'], $user['password'], $user['user_type']);
        } else {
            return null;
        }
    }

    // Authorization method
    public function hasPermission($requiredUserType) {
        return $this->userType === $requiredUserType;
    }
}
?>
