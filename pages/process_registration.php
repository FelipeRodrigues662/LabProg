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
    header('Location: user_login.php');
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
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../css/process_registration.css">
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

    <section2>
        <h2>Registro</h2>
        <div class="search-form-3">
            <input class="space-form-text-3" type="text" id="searchInput" placeholder="Buscar eventos">
            <button class="btn-search-3" id="searchButton">Buscar</button>
        </div>
        <form class="categoria" method="POST" action="">
            <div class="form-categories">
                <label class="label-categoria" for="category">Categoria:</label>
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
            <div class="pagamento-check">
                <label for="payment">Pagamento:</label>
                <input type="checkbox" id="payment" name="payment">

                <input type="hidden" id="event_id" name="event_id">
            </div>
            <button type="submit" class="btn-registrar">Registrar</button>
        </form>
    </section2>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#category').change(function () {
                var categoryId = $(this).val();

                if (categoryId !== '') {
                    $.ajax({
                        url: '../services/get_events.php',
                        method: 'POST',
                        data: { category_id: categoryId },
                        success: function (response) {
                            $('#eventsContainer').html(response);
                        }
                    });
                } else {
                    $('#eventsContainer').html('');
                }
            });

            $(document).on('click', '.event-link', function (e) {
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
                    success: function (response) {
                        $('#eventsContainer').html(response);
                    }
                });
            }

            // Lidar com o clique no botão de pesquisa
            $('#searchButton').click(function (e) {
                e.preventDefault();
                var searchText = $('#searchInput').val();
                performSearch(searchText);
            });

            // Lidar com a tecla Enter pressionada no campo de pesquisa
            $('#searchInput').keypress(function (e) {
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