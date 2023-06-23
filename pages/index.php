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
    <title>Event Management System - Home</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .events {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;
            margin-top: 20px;
        }

        .event {
            width: calc(25% - 20px);
            margin: 0 10px 20px;
            padding: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            text-decoration: none;
            color: inherit;
        }

        .event h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .event img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .event p {
            margin-bottom: 5px;
        }

        .search-form {
            margin-top: 10px;
            display: flex;
            justify-content: center;
        }

        .search-form input[type="text"] {
            padding: 5px;
            width: 50%;
        }

        .search-form button {
            padding: 5px 10px;
            background-color: #ccc;
            border: none;
            cursor: pointer;
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
    
    <form class="search-form" action="index.php" method="GET">
            <input type="text" name="query" placeholder="Search events" value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>

    <section class="events">
        <br>
        <?php
            if (!empty($events)) {
                foreach ($events as $event) {
                    $eventId = $event->getId();
                    $eventDetailsUrl = "event_details.php?id=$eventId";

                    echo "<a href='$eventDetailsUrl' class='event'>";
                    echo "<h3>" . $event->getTitle() . "</h3>";
                    echo "<img src='" . $event->getImages() . "' alt='Event Image'>";
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
    </section>
    
    <footer>
        
    </footer>
</body>
</html>
