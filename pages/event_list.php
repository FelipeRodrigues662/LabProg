<?php
session_start();

require_once '../classes/User.php';

if (!isset($_SESSION['user']) || (unserialize($_SESSION['user'])->getUserType() !== 'admin' && unserialize($_SESSION['user'])->getUserType() !== 'grant_admin')) {
    header('Location: ../services/unauthorized.php'); 
    exit();
}

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

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: 200px;
            padding: 5px;
            font-size: 14px;
        }

        .search-bar button[type="submit"] {
            padding: 5px 10px;
            font-size: 14px;
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
    
    <section class="event-list">
        <?php
        require_once __DIR__ . '/../database/connection.php';
        require_once '../classes/Event.php';

        // Verificar se a barra de pesquisa foi submetida
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $events = Event::searchEvents($search);
        } else {
            $events = Event::getAll();
        }

        echo "<div class='search-bar'>";
        echo "<form action='event_list.php' method='GET'>";
        echo "<input type='text' name='search' placeholder='Pesquisar evento'>";
        echo "<button type='submit'>Pesquisar</button>";
        echo "</form>";
        echo "</div>";

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
    </footer>
</body>
</html>
