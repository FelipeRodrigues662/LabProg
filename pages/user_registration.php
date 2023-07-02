<?php
require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = 'regular';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $user = new User($id, $name, $email, $hashedPassword, $userType);
    $user->save();

    header('Location: user_login.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registration - Page</title>
    <link rel="stylesheet" href="../css/registro_user.css">
</head>

<body>
    <div class="main-login">
        <section-right class="card-login">
            <h2>SignUp</h2>
            <form action="user_registration.php" method="POST">
                <div class="textfield">
                    <input placeholder="Name" type="text" name="name" id="name" required>
                </div>
                <div class="textfield">
                    <input placeholder="Email" type="email" name="email" id="email" required>
                </div>
                <div class="textfield">
                    <input placeholder="Password" type="password" name="password" id="password" required>
                </div>
                <br>
                <button class="btn-login" type="submit" value="Register">Register</button>
            </form>
            <p>Already have a account? <a href="user_login.php">SignIn</a></p>
        </section-right>

        <section-left>
            <h2>Register and enjoy</h2>
            <img class="image" src="../assets/animate.svg" alt="animacao">
        </section-left>
    </div>
</body>

</html>