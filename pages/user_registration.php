<?php
require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = 'regular'; // Defina o tipo de usuário desejado

    $user = new User($name, $email, $password, $userType);
    $user->save();

    echo 'User registered successfully.';
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
    
    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./add_event.php">Add Event</a></li>
            <li><a href="./user_login.php">Login</a></li>
            <li><a href="./user_registration.php">Register</a></li>
        </ul>
    </nav>
    
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
    
    <footer>
        <p>&copy; 2023 Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>
