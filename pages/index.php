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
    <title>Event Management System - Home</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
                <li><a href="./user_profile.php">Profile</a></li>
                <li><a href="./process_registration.php">Registrar evento</a></li>
                <li><a href="../services/logout.php">Logout</a></li>
            <?php else : ?>
                <li><a href="./user_login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <section class="events-carousel">
        <h2>Featured Events</h2>
        <div class="slick-slider">
            <?php
            require_once '../classes/Event.php';

            $events = Event::getAll();

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
        </div>
    </section>
    
    <footer>
        
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.events-carousel .slick-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>'
            });
        });
    </script>
</body>
</html>
