<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Category.php';
require_once '../classes/Event.php';
require_once '../classes/Registration.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
    $userId = $user->getId();
} else {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['event_id'];
    $paymentStatus = isset($_POST['payment']) ? 'paid' : 'pending';

    $registrationId = Registration::createRegistration($userId, $eventId, $paymentStatus);

    if ($registrationId) {
        header('Location: user_profile.php');
        exit();
    } else {
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Gerenciamento de Eventos - Registro</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <style>
        .event-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 20px;
        }

        .event {
            display: inline-block;
            text-align: center;
            margin-bottom: 20px;
        }

        .event img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

        .event h3 {
            margin-top: 10px;
        }

        .event-details.hidden {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Gerenciamento de Eventos</h1>
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
        <h2>Registro</h2>
        <div>
            <input type="text" id="searchInput" placeholder="Buscar eventos">
            <button id="searchButton">Buscar</button>
        </div>
        <form method="POST" action="">
            <div>
                <label for="category">Categoria:</label>
                <?php
                $categories = Category::getAll(); 

                if (!empty($categories)) {
                    echo "<select name='category' id='category'>";
                    echo "<option value=''>Selecione uma categoria</option>";

                    foreach ($categories as $category) {
                        echo "<option value='" . $category->getId() . "'>" . $category->getName() . "</option>";
                    }

                    echo "</select>";
                } else {
                    echo "<p>Nenhuma categoria encontrada.</p>";
                }
                ?>
            </div>

            <div id="eventsContainer">
  
            </div>

            <label for="payment">Pagamento:</label>
            <input type="checkbox" id="payment" name="payment">

            <input type="hidden" id="event_id" name="event_id">

            <button type="submit">Registrar</button>
        </form>
    </section>
    
    <footer>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category').change(function() {
                var categoryId = $(this).val();

                if (categoryId !== '') {
                    $.ajax({
                        url: '../services/get_events.php',
                        method: 'POST',
                        data: { category_id: categoryId },
                        success: function(response) {
                            $('#eventsContainer').html(response);
                        }
                    });
                } else {
                    $('#eventsContainer').html('');
                }
            });

            $(document).on('click', '.event-link', function(e) {
                e.preventDefault();
                var eventId = $(this).data('event-id');
                var eventDetails = $(this).siblings('.event-details');
                eventDetails.toggleClass('hidden');
                $('#event_id').val(eventId);
            });

            // Função para realizar a pesquisa
            function performSearch(searchText) {
                $.ajax({
                    url: '../services/search_events.php',
                    method: 'POST',
                    data: { search_text: searchText },
                    success: function(response) {
                        $('#eventsContainer').html(response);
                    }
                });
            }

            // Lidar com o clique no botão de pesquisa
            $('#searchButton').click(function(e) {
                e.preventDefault();
                var searchText = $('#searchInput').val();
                performSearch(searchText);
            });

            // Lidar com a tecla Enter pressionada no campo de pesquisa
            $('#searchInput').keypress(function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    var searchText = $(this).val();
                    performSearch(searchText);
                }
            });
        });
    </script>
</body>
</html>
