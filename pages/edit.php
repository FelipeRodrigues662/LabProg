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


        <style>
            h2 {
                margin-top: 30px;
                display: flex;
                justify-content: center;
            }

            section6 {
                display: flex;
                justify-content: center;
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                gap: 30px;
                margin: 10px;
            }

            form {
                background-color: #f1f1f1;
                width: 40%;
                padding: 30px;
                border-radius: 20px;
                margin-top: 20px;
                margin-bottom: 20px;
                display: flex;
                flex-direction: column;
                justify-content: right;
                align-items: right;
                box-shadow: 4px 4px 10px #495757;
            }

            form input {
                padding: 10px;
                margin-right: 5px;
                border-radius: 8px;
                border: 0.5px solid;
            }

            form textarea {
                padding: 10px;
                margin-right: 5px;
                border-radius: 8px;
                border: 0.5px solid;
            }

            .btn-update {
                width: 100%;
                background-color: transparent;
                padding: 10px;
                font-size: 16px;
                border-radius: 8px;
                border: none;
                background-color: #014bfd;
                color: #f1f1f1;
                cursor: pointer;
            }

            .btn-update:hover {
                background-color: #3858e9;
            }

            .btn-delete {
                width: 100%;
                background-color: transparent;
                padding: 10px;
                font-size: 16px;
                border-radius: 8px;
                border: none;
                background-color: #014bfd;
                color: #f1f1f1;
                cursor: pointer;
            }

            .btn-delete:hover {
                background-color: #3858e9;
            }

            .deletar {
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>


        <!DOCTYPE html>
        <html>

        <head>
            <title>Event Management System - Edit Event</title>
            <link rel="stylesheet" type="text/css" href="../css/style.css">
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