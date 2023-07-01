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

<style>
*{
    text-decoration: none;
    color: inherit;
}
body {
    font-family: 'Noto Sans', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    text-decoration: none;
    list-style: none;
    background-color: #ffffff;
}

/* Retirar rolagem da scrollbar */
body::-webkit-scrollbar{
    display: none;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Cabeçalho */
header {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    background-color: #014bfd;
    color: #f1f1f1;
    padding: 20px;
    text-align: center;
    border-radius: 0px 0px 16px 16px;
    box-shadow: 0px 0px 10px #333;
}

h1 {
    margin: 0;
}

/* Navegação */
nav ul {
    display: flex;
    list-style: none;
    padding: 0;
    margin-top: 10px;
}

nav ul li {
    margin-right: 20px;
}

nav ul li a{
    color: #f1f1f1;
    font-size: 18px;
    font-weight: 500;
    transition: .9s;
}

nav ul li a:hover{
    transform: scale(1.1);
    color: #f8f8f8;
}

    .register-event {
        border: 1px solid #f1f1f1;
        padding: 10px;
        border-radius: 10px;
        transition: 0.3s;
    }

    .register-event:hover {
        transform: scale(1.1);
        background-color: #3858e9;
    }

    .home-page {
        width: 28px;
        transition: 0.9s;
        transition: 0.3s;
    }

    .home-page:hover {
        transform: scale(1.1);
    }

    .leave-img {
        width: 30px;
        transition: 0.9s;
    }

    .leave-img:hover {
        transform: scale(1.1);
    }

    .perfil-img {
        width: 26px;
        transition: 0.9s;
    }

    .perfil-img:hover {
        transform: scale(1.1);
    }

    .subheader {
        display: flex;
        justify-content: space-around;
        margin-bottom: 20px;
    }

    .search-form-2 {
        margin: 24px;
        display: flex;
        flex-direction: row;
        justify-content: right;
    }

    .space-form-text-2 {
        padding: 12px;
        margin-right: 4px;
        border-radius: 8px;
        border: solid 0.5px;
    }

    .btn-search {
        width: 50px;
        background-color: transparent;
        padding: 2px;
        border-radius: 8px;
        border: none;
        background-color: #014bfd;
        color: #f1f1f1;
        cursor: pointer;
    }

    .btn-search:hover {
        background-color: #3858e9;
    }

    .search-form-3 {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin-bottom: 30px;
    }

    .space-form-text-3 {
        padding: 8px;
        margin-right: 4px;
        border-radius: 8px;
        border: solid 0.5px;
    }

    .btn-search-3 {
        background-color: transparent;
        padding: 10px;
        border-radius: 8px;
        border: none;
        background-color: #014bfd;
        color: #f1f1f1;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .btn-search-3:hover {
        background-color: #3858e9;
    }

</style>

<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
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

    <div class="eventos">
        <section1 class="events">
            <br>
            <?php
            if (!empty($events)) {
                foreach ($events as $event) {
                    $eventId = $event->getId();
                    $eventDetailsUrl = "event_details.php?id=$eventId";

                    echo "<a href='$eventDetailsUrl' class='event'>";
                    echo "<h3>" . $event->getTitle() . "</h3>";
                    echo "<img id='image-teste' src='" . $event->getImages() . "' alt='Event Image'>";
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
        </section1>
    </div>
</body>

</html>