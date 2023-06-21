<?php
require_once '../classes/User.php';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Autentica o usuário
    $user = User::authenticate($email, $password);

    if ($user) {
        // Autenticação bem-sucedida, redireciona para a página de perfil do usuário
        session_start();
        $_SESSION['user'] = $user;
        header('Location: user_profile.php');
        exit();
    } else {
        // Credenciais inválidas, exibe uma mensagem de erro
        $error = 'Credenciais inválidas. Tente novamente.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management System - User Login</title>
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
        <h2>Login</h2>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </section>
    
    <footer>
        <p>&copy; 2023 Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>
