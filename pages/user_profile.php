<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Registration.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$registeredEvents = [];
if ($user) {
    // Obter os eventos registrados pelo usuário
    $registeredEvents = Registration::getRegisteredEvents($user->getId());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management System - User Profile</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Event Management System</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <?php if ($user && $user->getUserType() === 'admin') : ?>
                <li><a href="./add_event.php">Add Event</a></li>
            <?php endif; ?>
            <li><a href="./user_profile.php">Profile</a></li>
            <li><a href="../services/logout.php">Logout</a></li>
        </ul>
    </nav>
    
    <section>
        <h2>User Profile</h2>
        <?php if ($user) : ?>
            <p>Name: <?php echo $user->getName(); ?></p>
            <p>Email: <?php echo $user->getEmail(); ?></p>
            <p>User Type: <?php echo $user->getUserType(); ?></p>

            <h3>Registered Events:</h3>
            <?php if (!empty($registeredEvents)) : ?>
                <ul>
                <?php foreach ($registeredEvents as $event) : ?>
                    <li>
                        <?php echo $event->getTitle(); ?><br>
                        Status: <?php echo $event->getPaymentStatus(); ?><br>
                        Event Date: <?php echo $event->getDate(); ?><br>
                        <a href="./reviews.php?event_id=<?php echo $event->getId(); ?>">Reviews</a><br>
                        <form action="../services/delete_event.php" method="post">
                            <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>">
                            <button type="submit">Delete Event</button>
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No registered events.</p>
            <?php endif; ?>
        <?php else : ?>
            <p>You are not logged in.</p>
        <?php endif; ?>
    </section>
    
    <footer>
        <p>&copy; 2023 Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>
