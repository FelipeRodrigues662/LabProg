<?php
require_once __DIR__ . '/../classes/User.php';

session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

// Verificar se o ID do usuário foi fornecido na URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $userToEdit = User::getById($userId);
}

// Verificar se o formulário de edição foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $userType = $_POST['user_type'];

    // Atualizar os dados do usuário
    $userToEdit = new User($userId, $name, $email, $password, $userType);
    $userToEdit->save();

    // Redirecionar de volta para a página de administração de usuários
    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/edit_user.css">
</head>

<body>
    <header>
        <h1></h1>
        <nav>
            <ul>
                <li><a href="./index.php"><img class="home-page" src="../assets/home.png" title="Home"></a></li>
                <?php if ($user instanceof User && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')): ?>
                    <li><a class="register-event" href="../pages/add_event.php">Add Event</a></li>
                <?php endif; ?>
                <?php if ($user instanceof User): ?>
                    <li><a class="register-event" href="../pages/process_registration.php">Registrar evento</a></li>
                    <li><a href="../pages/user_profile.php"><img class="perfil-img" src="../assets/perfil.png"
                                title="Profile"></a>
                    </li>
                    <li><a href="../services/logout.php"><img class="leave-img" src="../assets/sair.png"
                                title="SignOut"></a></li>
                <?php else: ?>
                    <li><a href="../pages/user_login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <h1 class="text">Edit User</h1>

    <section5>
        <?php if ($userToEdit): ?>
            <form action="edit_user.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $userToEdit->getId(); ?>">
                <input type="text" name="name" value="<?php echo $userToEdit->getName(); ?>" required><br>
                <input type="email" name="email" value="<?php echo $userToEdit->getEmail(); ?>" required><br>
                <select name="user_type" required>
                    <option value="admin" <?php if ($userToEdit->getUserType() === 'admin')
                        echo 'selected'; ?>>Admin</option>
                    <option value="grant_admin" <?php if ($userToEdit->getUserType() === 'grant_admin')
                        echo 'selected'; ?>>Grant
                        Admin</option>
                    <option value="user" <?php if ($userToEdit->getUserType() === 'user')
                        echo 'selected'; ?>>User</option>
                </select><br>
                <button class="btn-save" type="submit">Save</button>
            </form>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </section5>
</body>

</html>