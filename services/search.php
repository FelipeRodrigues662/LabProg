<?php
require_once '../classes/Event.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Use o método estático da classe Event para obter os eventos correspondentes à pesquisa
    $events = Event::searchEvents($query);
} else {
    // Redirecione de volta para a página inicial se nenhum termo de pesquisa for fornecido
    header("Location: index.php");
    exit();
}
?>
