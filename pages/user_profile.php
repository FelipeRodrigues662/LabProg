<?php
// Verifica se o usuário está autenticado, caso contrário, redireciona para a página de login
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: user_login.php');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management System - User Profile</title>
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
    
    <section>
        <h2>User Profile</h2>
        <p>Name: <?php echo $user->getName(); ?></p>
        <p>Email: <?php echo $user->getEmail(); ?></p>
        <p>User Type: <?php echo $user->getUserType(); ?></p>
        
        <a href="logout.php">Logout</a>
    </section>
    
    <footer>
        <p>&copy; 2023 Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>
