<?php
require_once '../classes/Event.php';

if (isset($_POST['search_text'])) {
    $searchText = $_POST['search_text'];

    $events = Event::searchEvents($searchText);

    if (!empty($events)) {
        foreach ($events as $event) {
            echo '<div class="event">';
            echo '<a class="event-link" href="#" data-event-id="' . $event->getId() . '">';
            echo '<h3>' . $event->getTitle() . '</h3>';
            echo '<img src="' . $event->getImages() . '" alt="' . $event->getTitle() . '">';
            echo '</a>';
            echo '<div class="event-details hidden">';
            
            echo '<p>' . $event->getDescription() . '</p>';
            echo '<p>Date: ' . $event->getDate() . '</p>';
            echo '<p>Time: ' . $event->getTime() . '</p>';
            echo '<p>Location: ' . $event->getLocation() . '</p>';
            echo '<p>Price: ' . $event->getPrice() . '</p>';

            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>Nenhum evento encontrado.</p>";
    }
} else {
    echo "<p>Nenhum termo de pesquisa fornecido.</p>";
}
?>
