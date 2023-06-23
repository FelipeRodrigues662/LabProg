<?php
require_once '../classes/User.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = '';
    if (empty($email)) {
        $error .= 'Por favor, informe o e-mail.<br>';
    }
    if (empty($password)) {
        $error .= 'Por favor, informe a senha.<br>';
    }

    if (empty($error)) {
        $user = User::authenticate($email, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = serialize($user); 
            header('Location: user_profile.php');
            exit();
        } else {
            $error = 'Credenciais inválidas.';
        }
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

    
    <section>
        <h2>Login</h2>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>

        <p>Não possui uma conta? <a href="./user_registration.php">Registrar-se</a></p>
    </section>
    
    <footer>
    </footer>
</body>
</html>
