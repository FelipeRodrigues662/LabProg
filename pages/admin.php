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
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <header>
        <h1>Event System</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <?php if ($user instanceof User && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')) : ?>
                <li><a href="../pages/add_event.php">Add Event</a></li>
            <?php endif; ?>
            <?php if ($user instanceof User) : ?>
                <li><a href="../pages/process_registration.php">Registrar evento</a></li>
                <li><a href="../pages/user_profile.php">Profile</a></li>
                <li><a href="../services/logout.php">Logout</a></li>
            <?php else : ?>
                <li><a href="../pages/user_login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <h1>Usuários</h1>

    <form action="admin.php" method="get">
        <input type="text" name="search" placeholder="Pesquisar por nome">
        <button type="submit">Pesquisar</button>
    </form>

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
                <td><?php echo $user->getName(); ?></td>
                <td><?php echo $user->getEmail(); ?></td>
                <td><?php echo $user->getUserType(); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user->getId(); ?>">Editar</a>
                    <a href="../services/delete_user.php?id=<?php echo $user->getId(); ?>">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Event List</h2>
    <a href="event_list.php">Ver Lista de Eventos</a>
    <footer>
    </footer>
</body>
</html>
