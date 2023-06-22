<?php
session_start();
require_once '../classes/Registration.php';

if (isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    
    // Verificar se o usuário tem permissão para excluir o evento (adicionar lógica apropriada aqui)
    
    // Excluir o evento
    Registration::deleteById($eventId);

    // Redirecionar de volta para o perfil do usuário
    header('Location: ../pages/user_profile.php');
    exit();
} else {
    // Se não foi fornecido o ID do evento, redirecionar para o perfil do usuário
    header('Location: ../pages/user_profile.php');
    exit();
}
?>