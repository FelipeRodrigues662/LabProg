<?php
session_start();

require_once '../classes/User.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Details</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
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
    
    <section class="event-details">
        <?php
        require_once __DIR__ . '/../database/connection.php';
        require_once '../classes/Event.php';


        if (isset($_GET['id'])) {
            $eventId = $_GET['id'];
            $event = Event::getById($eventId);

            if ($event) {
                echo "<h2>{$event->getTitle()}</h2>";
                echo "<img src='{$event->getImages()}' alt='Event Image'>";
                echo "<p>Description: {$event->getDescription()}</p>";
                echo "<p>Date: {$event->getDate()}</p>";
                echo "<p>Time: {$event->getTime()}</p>";
                echo "<p>Location: {$event->getLocation()}</p>";

                // Botão para redirecionar para process_registration.php
                echo "<form action='../pages/process_registration.php' method='post'>";
                echo "<input type='hidden' name='event_id' value='{$eventId}'>";
                echo "<input type='submit' name='payment' value='Fazer Inscrição'>";
                echo "</form>";
            } else {
                echo "<p>Event not found.</p>";
            }
        } else {
            echo "<p>Invalid event ID.</p>";
        }
        ?>
    </section>
    <br>
    
</body>
</html>
