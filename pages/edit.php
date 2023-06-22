<?php
session_start();
require_once '../classes/User.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Aqui você pode recuperar o evento do banco de dados com base no ID recebido
    // e preencher os campos do formulário com os valores existentes do evento

    // Exemplo:
    require_once __DIR__ . '/../database/connection.php';
    require_once '../classes/Event.php';

    $event = Event::getById($eventId);

    if ($event) {
        // Formulário para edição do evento
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Event Management System - Edit Event</title>
            <link rel="stylesheet" type="text/css" href="../css/style.css">
        </head>
        <body>
            <header>
                <h1>Event Management System</h1>
            </header>
        
            <nav>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./add_event.php">Add Event</a></li>
                    <li><a href="./process_registration.php">Registrar evento</a></li>
                    <li><a href="./user_profile.php">Profile</a></li>
                    <li><a href="../services/logout.php">Logout</a></li>
                </ul>
            </nav>
        
            <section>
                <h2>Edit Event</h2>
                <form action="../services/update_event.php" method="POST">
                    <input type="hidden" name="id" value="{$eventId}">
                    <label>Title:</label>
                    <input type="text" name="title" value="{$event->getTitle()}"><br>
                    <label>Description:</label>
                    <textarea name="description">{$event->getDescription()}</textarea><br>
                    <label>Date:</label>
                    <input type="text" name="date" value="{$event->getDate()}"><br>
                    <label>Time:</label>
                    <input type="text" name="time" value="{$event->getTime()}"><br>
                    <label>Location:</label>
                    <input type="text" name="location" value="{$event->getLocation()}"><br>
                    <label>Category:</label>
                    <input type="text" name="category" value="{$event->getCategoryId()}"><br>
                    <label>Price:</label>
                    <input type="text" name="price" value="{$event->getPrice()}"><br>
                    <label>Images:</label>
                    <input type="text" name="images" value="{$event->getImages()}"><br>
            
                    <button type="submit">Update</button>
                </form>

                <br>

                <h2>Delete Event</h2>
                <form action="../services/delete_event_registration.php" method="POST" onsubmit="return confirmDelete();">
                    <input type="hidden" name="id" value="{$eventId}">
                    <button type="submit">Delete</button>
                </form>
            </section>

            <script>
                function confirmDelete() {
                    return confirm("Are you sure you want to delete this event?");
                }
            </script>

        
            <footer>
        
            </footer>
        </body>
        </html>
        HTML;
    } else {
        echo "<p>Event not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
