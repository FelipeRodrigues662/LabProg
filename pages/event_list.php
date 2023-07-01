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
    <link rel="stylesheet" type="text/css" href="../css/event_list.css">
</head>

<body>
    <header>
        <h1></h1>
        <nav>
            <ul>
                <li><a href="./index.php"><img class="home-page" src="../assets/home.png" title="Home"></a></li>
                <?php if ($user instanceof User && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')): ?>
                    <li><a class="register-event" href="../pages/add_event.php">Add Event</a></li>
                <?php endif; ?>
                <?php if ($user instanceof User): ?>
                    <li><a class="register-event" href="../pages/process_registration.php">Registrar evento</a></li>
                    <li><a href="../pages/user_profile.php"><img class="perfil-img" src="../assets/perfil.png"
                                title="Profile"></a>
                    </li>
                    <li><a href="../services/logout.php"><img class="leave-img" src="../assets/sair.png"
                                title="SignOut"></a></li>
                <?php else: ?>
                    <li><a href="../pages/user_login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

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
        echo "<h2>Event List</h2>";
        echo "<div class='search-bar'>";
        echo "<form action='event_list.php' method='GET'>";
        echo "<input type='text' name='search' placeholder='Search event'>";
        echo "<button class='btn-search' type='submit'>Search</button>";
        echo "</form>";
        echo "</div>";

        if (!empty($events)) {

            echo "<div class='event-container'>";

            foreach ($events as $event) {
                $eventId = $event->getId();
                $eventTitle = $event->getTitle();
                $eventImage = $event->getImages();

                echo "<div class='event'>";
                echo "<a href='./edit.php?id={$eventId}'>";
                echo "<h3>{$eventTitle}</h3>";
                echo "<img src='{$eventImage}' alt='Event Image'>";
                echo "</a>";
                echo "</div>";
            }

            echo "</div>";
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </section>
</body>

</html>