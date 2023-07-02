<?php
require_once __DIR__ . '/../classes/User.php';

session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if (!isset($_SESSION['user']) || ($user->getUserType() !== 'admin' && $user->getUserType() !== 'grant_admin')) {
    header('Location: ../services/unauthorized.php');
    exit();
}

// Verificar se a barra de pesquisa foi submetida
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $users = User::getUsers($search);
} else {
    $users = User::getUsers();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
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

    <h1>Users</h1>
    <form action="admin.php" method="get">
        <input type="text" name="search" placeholder="Search a User">
        <button class="btn-search" type="submit">Search</button>
    </form>

    <section10>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo de Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php echo $user->getName(); ?>
                        </td>
                        <td>
                            <?php echo $user->getEmail(); ?>
                        </td>
                        <td>
                            <?php echo $user->getUserType(); ?>
                        </td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user->getId(); ?>"><img class="perfil-img"
                                    src="../assets/edit.png" title="Edit User"></a>
                            <a href="../services/delete_user.php?id=<?php echo $user->getId(); ?>"><img class="perfil-img"
                                    src="../assets/delete.png" title="Delete User"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section10>

    <div class="ver">
        <h2>Event List</h2>
        <a href="event_list.php"><button class="btn-events"> Ver Lista de Eventos </button></a>
    </div>
</body>

</html>