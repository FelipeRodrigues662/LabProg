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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <header>
        <h1>Event Management System</h1>
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
    
    <section>
        <h2>User Profile</h2>
        <?php if ($user) : ?>
            <p>Name: <?php echo $user->getName(); ?></p>
            <p>Email: <?php echo $user->getEmail(); ?></p>
            <p>User Type: <?php echo $user->getUserType(); ?></p>
            <br>
            <h3>Registered Events:</h3>
            <?php if (!empty($registeredEvents)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Event Title</th>
                            <th>Event Date</th>
                            <th>Reviews</th>
                            <th>QR Code</th>
                            <th>Delete Event</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registeredEvents as $event) : ?>
                            <tr>
                                <td><?php echo $event->getTitle(); ?></td>
                                <td><?php echo $event->getDate(); ?></td>
                                <td><a href="./reviews.php?event_id=<?php echo $event->getId(); ?>">Reviews</a></td>
                                <td>
                                    <a href="../services/qrcode_page.php?user_id=<?php echo $user->getId(); ?>&event_id=<?php echo $event->getId(); ?>">View QR Code</a>
                                </td>
                                <td>
                                    <form action="../services/delete_event.php" method="post">
                                        <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>">
                                        <button type="submit">Delete Event</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
