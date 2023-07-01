<?php
session_start();

require_once '../classes/User.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

?>


<style>
    .event-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 60px 160px 10px 160px;
        background-color: #f1f1f1;
        border-radius: 30px;
        padding: 10px;
        box-shadow: 4px 4px 20px black;
    }

    .img-eventdetails {
        width: 500px;
        border-radius: 20px
    }

    .btn-subscription {
        background-color: transparent;
        padding: 10px;
        border-radius: 8px;
        border: none;
        background-color: #014bfd;
        color: #f1f1f1;
        cursor: pointer;
        margin-bottom: 1px;
        transition: 0.9s;
    }

    .btn-subscription:hover {
        background-color: #3858e9;
        transform: scale(1.1);
    }
</style>


<!DOCTYPE html>
<html>

<head>
    <title>Event Details</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <header>
        <h1>Event System</h1>
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


    <section3 class="event-details">
        <?php
        require_once __DIR__ . '/../database/connection.php';
        require_once '../classes/Event.php';


        if (isset($_GET['id'])) {
            $eventId = $_GET['id'];
            $event = Event::getById($eventId);

            if ($event) {
                echo "<h2>{$event->getTitle()}</h2>";
                echo "<img class='img-eventdetails' src='{$event->getImages()}' alt='Event Image'>";
                echo "<p>Description: {$event->getDescription()}</p>";
                echo "<p>Date: {$event->getDate()}</p>";
                echo "<p>Time: {$event->getTime()}</p>";
                echo "<p>Location: {$event->getLocation()}</p>";

                // Botão para redirecionar para process_registration.php
                echo "<form  action='../pages/process_registration.php' method='post'>";
                echo "<input type='hidden'  name='event_id' value='{$eventId}'>";
                echo "<input class='btn-subscription' type='submit' name='payment' value='Subscribe'>";
                echo "</form>";
            } else {
                echo "<p>Event not found.</p>";
            }
        } else {
            echo "<p>Invalid event ID.</p>";
        }
        ?>
    </section3>
    <br>

</body>

</html>