<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Review.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if (!$user) {
    // Redirecionar para a página de login se o usuário não estiver logado
    header("Location: ../pages/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['event_id']) && isset($_POST['score']) && isset($_POST['comment'])) {
        $event_id = $_POST['event_id'];
        $score = $_POST['score'];
        $comment = $_POST['comment'];

        // Criar uma nova instância de Review
        $review = new Review($user->getId(), $event_id, $score, $comment);

        // Salvar a avaliação no banco de dados
        $review->save();

        // Redirecionar de volta para a página de reviews do evento
        header("Location: ../pages/reviews.php?event_id=" . $event_id);
        exit;
    }
}

// Redirecionar para a página inicial se não houver dados de avaliação enviados
header("Location: ../pages/index.php");
exit;
?>
