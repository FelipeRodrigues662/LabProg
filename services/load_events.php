<?php
require_once '../classes/Event.php';

// Verifica se o parâmetro category_id foi enviado
if (isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];

    // Obtém os eventos com base na categoria selecionada
    $events = Event::getEventsByCategory($categoryId);

    // Gera as opções dos eventos
    $options = "<option value=''>Select an event</option>";
    foreach ($events as $event) {
        $options .= "<option value='" . $event['id'] . "'>" . $event['name'] . "</option>";
    }

    echo $options;
}
?>
