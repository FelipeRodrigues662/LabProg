<?php
session_start();

require_once '../classes/User.php';

if (!isset($_SESSION['user']) || (unserialize($_SESSION['user'])->getUserType() !== 'admin' && unserialize($_SESSION['user'])->getUserType() !== 'grant_admin')) {
    header('Location: ../services/unauthorized.php'); // Redireciona para a página "Sem Autorização"
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Exemplo:
    require_once __DIR__ . '/../database/connection.php';
    require_once '../classes/Event.php';

    $event = Event::getById($eventId);

    if ($event) {
 
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Event Management System - Edit Event</title>
            <link rel="stylesheet" type="text/css" href="../css/style.css">
        </head>
        <body>
            <header>
                <h1>Event Management System</h1>
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
        
            <section>
                <h2>Edit Event</h2>
                <form action="../services/update_event.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $eventId; ?>">
                    <label>Title:</label>
                    <input type="text" name="title" value="<?php echo $event->getTitle(); ?>"><br>
                    <label>Description:</label>
                    <textarea name="description"><?php echo $event->getDescription(); ?></textarea><br>
                    <label>Date:</label>
                    <input type="text" name="date" value="<?php echo $event->getDate(); ?>"><br>
                    <label>Time:</label>
                    <input type="text" name="time" value="<?php echo $event->getTime(); ?>"><br>
                    <label>Location:</label>
                    <input type="text" name="location" value="<?php echo $event->getLocation(); ?>"><br>
                    <label>Category:</label>
                    <input type="text" name="category" value="<?php echo $event->getCategoryId(); ?>"><br>
                    <label>Price:</label>
                    <input type="text" name="price" value="<?php echo $event->getPrice(); ?>"><br>
                    <label>Images:</label>
                    <input type="text" name="images" value="<?php echo $event->getImages(); ?>"><br>
            
                    <button type="submit">Update</button>
                </form>

                <br>

                <h2>Delete Event</h2>
                <form action="../services/delete_event_registration.php" method="POST" onsubmit="return confirmDelete();">
                    <input type="hidden" name="id" value="<?php echo $eventId; ?>">
                    <button type="submit">Delete</button>
                </form>
            </section>

            <script>
                function confirmDelete() {
                    return confirm("Tem certeza que deseja deletar o evento?");
                }
            </script>

        
            <footer>
        
            </footer>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Evento não encontrado.</p>";
    }
} else {
    echo "<p>404.</p>";
}
?>
