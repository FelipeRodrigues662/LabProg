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
            header('Location: index.php');
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
    <title>Login - Page</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="main-login">
        <section-left>
            <h2>SignIn</h2>
            <img class="image" src="../assets/animate2.svg" alt="animacao">
        </section-left>

        <section-right class="card-login">
            <h2>Login</h2>
            <?php if (!empty($error)): ?>
                <p class="error">
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="textfield">
                    <input placeholder="Email" type="email" id="email" name="email" required>
                </div>
                <div class="textfield">
                    <input placeholder="Password" type="password" id="password" name="password" required>
                </div>
                <br>
                <button class="btn-login" type="submit">Login</button>
            </form>

            <p>Don't have a account? <a href="./user_registration.php">SignUp now</a></p>
        </section-right>
    </div>
</body>

</html>