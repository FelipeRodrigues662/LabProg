<?php
require_once __DIR__ . '/../database/connection.php';
require_once '../classes/Event.php';
require_once '../classes/Review.php';
require_once '../classes/Registration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $eventId = $_POST['id'];


        // Excluir todas as registrations relacionadas ao evento
        Registration::deleteById($eventId);
        Review::deleteById($eventId);
        Event::deleteById($eventId);

        // Redirecionar para a página principal ou outra página adequada
        header("Location: ../pages/index.php");
        exit();

    echo "<p>Invalid request.</p>";
}
?>
