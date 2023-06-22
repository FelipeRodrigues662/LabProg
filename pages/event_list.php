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
    <title>Event List</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <style>
        .event-container {
            display: flex;
            flex-wrap: wrap;
        }

        .event {
            width: 25%;
            padding: 10px;
            box-sizing: border-box;
        }

        .event a {
            text-decoration: none;
            color: #333;
        }

        .event img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .event h3 {
            margin: 0;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Event System</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <?php if ($user instanceof User && $user->getUserType() === 'admin') : ?>
                <li><a href="./add_event.php">Add Event</a></li>
            <?php endif; ?>
            <?php if ($user instanceof User) : ?>
                <li><a href="./process_registration.php">Registrar evento</a></li>
                <li><a href="./user_profile.php">Profile</a></li>
                <li><a href="../services/logout.php">Logout</a></li>
            <?php else : ?>
                <li><a href="./user_login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <section class="event-list">
        <?php
        require_once __DIR__ . '/../database/connection.php';
        require_once '../classes/Event.php';

        $events = Event::getAll();

        if (!empty($events)) {
            echo "<h2>Event List</h2>";
            echo "<div class='event-container'>";

            foreach ($events as $event) {
                $eventId = $event->getId();
                $eventTitle = $event->getTitle();
                $eventImage = $event->getImages();

                echo "<div class='event'>";
                echo "<a href='./edit.php?id={$eventId}'>";
                echo "<img src='{$eventImage}' alt='Event Image'>";
                echo "<h3>{$eventTitle}</h3>";
                echo "</a>";
                echo "</div>";
            }

            echo "</div>";
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </section>
    
    <footer>
        <p>&copy; 2023 Event System</p>
    </footer>
</body>
</html>
