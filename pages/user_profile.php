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
    $registeredEvents = Registration::getRegisteredEvents($user->getId());
}
?>

<!DOCTYPE html>
<html>


<style>
    section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .info-users {
        display: flex;
        flex-direction: column-reverse;
        background-color: #f1f1f1;
        border-radius: 20px;
        padding: 40px;
        align-items: center;
        cursor: pointer;
        transition: 0.9s;
    }

    .info-users:hover {
        transform: scale(1.1);
        background-color: #f8f8f8;
    }

    table {
        width: 99%;
        border-collapse: collapse;
        border-radius: 20px;
        margin-bottom: 50px;
    }

    span {
        font-weight: bold;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-right: 0.5px solid #ddd;
        border-bottom: 1px solid #ddd;
    }

    #review {
        color: #014bfd;
        transition: 0.9s;
    }

    #review:hover {
        color: #7c8ac5;
        transform: scale(1.1);
    }

    th {
        background-color: #014bfd;
        color: #f2f2f2;
    }

    tr {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    tr:hover {
        background-color: #f8f8f8;
    }

    .btn-qrcode {
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

    .btn-qrcode:hover {
        background-color: #3858e9;
        transform: scale(1.1);
    }

    .btn-delete-event {
        background-color: transparent;
        padding: 10px;
        border-radius: 8px;
        border: none;
        background-color: red;
        color: #f1f1f1;
        cursor: pointer;
        margin-bottom: 1px;
        transition: 0.9s;
    }

    .btn-delete-event:hover {
        background-color: red;
        transform: scale(1.1);
    }

    .btn-typeuser {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        padding: 10px;
        border-radius: 8px;
        border: none;
        background-color: #014bfd;
        color: #f1f1f1;
        cursor: pointer;
        margin-bottom: 10px;
        transition: 0.9s;
    }

    .btn-typeuser:hover {
        background-color: #3858e9;
        transform: scale(1.1);
    }

    .imagem {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .img-user {
        width: 100px;
        border-radius: 50px;
        margin-bottom: 20px;
    }

    .img-delete {
        width: 50px;

    }

    .btn-deleteevent {
        border: none;
        background-color: transparent;
        cursor: pointer;
        transition: 0.9s;
    }

    .btn-deleteevent:hover {
        transform: scale(1.1);
    }
</style>


<head>
    <title>User Profile</title>
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
        <h2>User Profile</h2>
        <div class="info-users">
            <?php if ($user): ?>
                <p><span>Name:</span>
                    <?php echo $user->getName(); ?>
                </p>
                <p><span>Email:</span>
                    <?php echo $user->getEmail(); ?>
                </p>
                <p><span>User Type:</span>
                    <?php echo $user->getUserType(); ?>
                </p>

                <?php if ($user instanceof User && $user->getUserType() === 'grant_admin'): ?>
                    <a href="./admin.php"><button class="btn-typeuser">Admin Pannel</button></a>
                <?php endif; ?>
                <?php if ($user instanceof User && $user->getUserType() === 'admin'): ?>
                    <a href="./event_list.php"><button class="btn-typeuser">Event List</button></a>
                <?php endif; ?>
                <div class="imagem">
                    <img class="img-user" src="../assets/user.png" title="User Image" alt="user">
                </div>
            </div>
            <br>
            <h3>Registered Events:</h3>
            <?php if (!empty($registeredEvents)): ?>
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
                        <?php foreach ($registeredEvents as $event): ?>
                            <tr>
                                <td>
                                    <?php echo $event->getTitle(); ?>
                                </td>
                                <td>
                                    <?php echo $event->getDate(); ?>
                                </td>
                                <td><a id="review" href="./reviews.php?event_id=<?php echo $event->getId(); ?>">Reviews</a></td>
                                <td><button type="submit" class="btn-qrcode">
                                        <a
                                            href="../services/qrcode_page.php?user_id=<?php echo $user->getId(); ?>&event_id=<?php echo $event->getId(); ?>">View
                                            QR Code</a></button>
                                </td>
                                <td>
                                    <form action="../services/delete_event.php" method="post">
                                        <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>">
                                        <button class="btn-deleteevent" type="submit" title="Delete"><img class="img-delete"
                                                src="../assets/delete.png" title="Delete Event" alt=""></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No registered events.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>You are not logged in.</p>
        <?php endif; ?>
    </section>
</body>

</html>