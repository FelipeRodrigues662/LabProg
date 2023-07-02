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
            <link rel="stylesheet" href="../css/edit.css">
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

            <section>
                <h2>Edit Event</h2>
                <section6>
                    <form action="../services/update_event.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $eventId; ?>">
                        <label>Title:</label>
                        <input placeholder="Event Title" type="text" name="title" value="<?php echo $event->getTitle(); ?>"><br>
                        <label>Description:</label>
                        <textarea placeholder="Event Description"
                            name="description"><?php echo $event->getDescription(); ?></textarea><br>
                        <label>Date:</label>
                        <input placeholder="Data" type="text" name="date" value="<?php echo $event->getDate(); ?>"><br>
                        <label>Time:</label>
                        <input placeholder="Time" type="text" name="time" value="<?php echo $event->getTime(); ?>"><br>
                        <label>Location:</label>
                        <input placeholder="Location" type="text" name="location"
                            value="<?php echo $event->getLocation(); ?>"><br>
                        <label>Category:</label>
                        <input placeholder="Category" type="text" name="category"
                            value="<?php echo $event->getCategoryId(); ?>"><br>
                        <label>Price:</label>
                        <input placeholder="Price" type="text" name="price" value="<?php echo $event->getPrice(); ?>"><br>
                        <label>Image Link:</label>
                        <input placeholder="Image Link" type="text" name="images"
                            value="<?php echo $event->getImages(); ?>"><br>

                        <button class="btn-update" type="submit">Update</button>
                    </form>
                </section6>

                <br>

                <h2>Delete Event</h2>
                <div class="deletar">
                    <form action="../services/delete_event_registration.php" method="POST" onsubmit="return confirmDelete();">
                        <input type="hidden" name="id" value="<?php echo $eventId; ?>">
                        <button class="btn-delete" type="submit">Delete</button>
                    </form>
                </div>
            </section>

            <script>
                function confirmDelete() {
                    return confirm("Tem certeza que deseja deletar o evento?");
                }
            </script>
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