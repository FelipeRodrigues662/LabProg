<?php
require_once '../classes/Event.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];

    $events = Event::getEventsByCategory($categoryId);

    if (!empty($events)) {
        foreach ($events as $event) {
            echo '<div class="event">';
            echo '<a class="event-link" href="#" data-event-id="' . $event['id'] . '">';
            echo '<h3>' . $event['title'] . '</h3>';
            echo '<img src="' . $event['images'] . '" alt="' . $event['title'] . '">';
            echo '</a>';
            echo '<div class="event-details hidden">';
            
            echo '<p>' . $event['description'] . '</p>';
            echo '<p>Date: ' . $event['date'] . '</p>';
            echo '<p>Time: ' . $event['time'] . '</p>';
            echo '<p>Location: ' . $event['location'] . '</p>';
            echo '<p>Price: ' . $event['price'] . '</p>';
            // Adicione aqui outros detalhes do evento que deseja exibir
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No events found for this category.</p>';
    }
}
?>
