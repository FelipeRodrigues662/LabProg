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
    $userToDelete = User::getById($userId);

    // Verificar se o usuário a ser excluído existe e tem permissão para excluir
    if ($userToDelete && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')) {
        // Excluir o usuário
        $userToDelete->deleteById($userId);

        // Redirecionar de volta para a página de administração de usuários
        header('Location: ../pages/admin.php');
        exit();
    } else {
        // Redirecionar para a página de acesso não autorizado
        header('Location: ../services/unauthorized.php');
        exit();
    }
} else {
    // Redirecionar de volta para a página de administração de usuários
    header('Location: ../pages/admin.php');
    exit();
}
?>