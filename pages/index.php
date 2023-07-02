<?php
session_start();

require_once '../classes/User.php';
require_once '../classes/Event.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$events = Event::getAll();

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $events = Event::searchEvents($query);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

    <div class="subheader">
        <div class="title">
            <h2>Principais eventos</h2>
        </div>

        <form class="search-form-2" action="index.php" method="GET">
            <input class="space-form-text-2" type="text" name="query" placeholder="Search events"
                value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
            <button class="btn-search" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="eventos-geral">
        <br>
        <?php
        if (!empty($events)) {
            foreach ($events as $event) {
                $eventId = $event->getId();
                $eventDetailsUrl = "event_details.php?id=$eventId";

                echo "<a href='$eventDetailsUrl' class='event'>";
                echo "<h3>" . $event->getTitle() . "</h3>";
                echo "<img class='image-teste' src='" . $event->getImages() . "' alt=''>";
                echo "<p>" . $event->getDescription() . "</p>";
                echo "<p>Date: " . $event->getDate() . "</p>";
                echo "<p>Time: " . $event->getTime() . "</p>";
                echo "<p>Location: " . $event->getLocation() . "</p>";
                echo "</a>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </div>
</body>

</html>