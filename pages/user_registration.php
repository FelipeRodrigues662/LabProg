<?php
require_once '../classes/User.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = 'regular';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $user = new User($id ,$name, $email, $hashedPassword, $userType);
    $user->save();

    header('Location: user_login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management System - User Registration</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Event Management System</h1>
    </header>
    
    <form action="user_registration.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Register">
    </form>
    <br>
    <a href="user_login.php">Voltar para o Login</a>
    <footer>
    </footer>
</body>
</html>
