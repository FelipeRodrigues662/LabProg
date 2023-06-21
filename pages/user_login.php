<?php
require_once '../classes/User.php';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Realiza as validações
    $error = '';
    if (empty($email)) {
        $error .= 'Por favor, informe o e-mail.<br>';
    }
    if (empty($password)) {
        $error .= 'Por favor, informe a senha.<br>';
    }

    if (empty($error)) {
        // Autentica o usuário
        $user = User::authenticate($email, $password);

        if ($user) {
            // Autenticação bem-sucedida, redireciona para a página de perfil do usuário
            session_start();
            $_SESSION['user'] = serialize($user); // Armazena o objeto User na sessão
            header('Location: user_profile.php');
            exit();
        } else {
            // Credenciais inválidas, exibe uma mensagem de erro
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
    </section>
    
    <footer>
        <p>&copy; 2023 Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>
