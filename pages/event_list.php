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
            gap: 10px;
            justify-content: center;
        }

        .event {
            display: flex;
            flex-direction: column;
            width: 30%;
            padding: 20px;
        }

        .event img {
            width: 100%;
            border-radius: 10px;
            height: auto;
            margin-bottom: 10px;
        }

        .event h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }

        h1 {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        form {
            margin-top: 20px;
            margin-bottom: 20px;
            margin-right: 80px;
            display: flex;
            flex-direction: row;
            justify-content: right;
            align-items: right;
        }

        form input {
            padding: 10px;
            margin-right: 5px;
            border-radius: 8px;
            border: 0.5px solid;
        }

        .btn-search {
            width: 60px;
            background-color: transparent;
            padding: 4px;
            border-radius: 8px;
            border: none;
            background-color: #014bfd;
            color: #f1f1f1;
            cursor: pointer;
        }

        .btn-search:hover {
            background-color: #3858e9;
        }
    </style>
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