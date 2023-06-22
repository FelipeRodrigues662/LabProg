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
    // Redirecionar o usuário para fazer o login, se necessário
    header('Location: login.php');
    exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['event_id']; // Alterado para event_id
    $paymentStatus = isset($_POST['payment']) ? 'paid' : 'pending';

    // Realizar a inserção na tabela de registros
    $registrationId = Registration::createRegistration($userId, $eventId, $paymentStatus);

    if ($registrationId) {
        // Registro criado com sucesso
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
    <title>Event Management System - Registration</title>
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
        <h2>Registration</h2>
        <form method="POST" action="">
            <div>
                <label for="category">Category:</label>
                <!-- Exemplo de como obter dinamicamente as categorias do banco de dados -->
                <?php
                $categories = Category::getAll(); // Obtenha todas as categorias do banco de dados

                if (!empty($categories)) {
                    echo "<select name='category' id='category' required>";
                    echo "<option value=''>Select a category</option>";

                    foreach ($categories as $category) {
                        echo "<option value='" . $category->getId() . "'>" . $category->getName() . "</option>";
                    }

                    echo "</select>";
                } else {
                    echo "<p>No categories found.</p>";
                }
                ?>
            </div>

            <div id="eventsContainer">
                <!-- Aqui será exibida a lista de eventos -->
            </div>

            <label for="payment">Payment:</label>
            <input type="checkbox" id="payment" name="payment">

            <!-- Campo oculto para enviar o ID do evento selecionado -->
            <input type="hidden" id="event_id" name="event_id">

            <button type="submit">Register</button>
        </form>
    </section>
    
    <footer>
        <p>&copy; 2023 Event Management System. All rights reserved.</p>
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
                $('#event_id').val(eventId); // Define o valor do input "event_id" com o ID do evento selecionado
            });
        });
    </script>
</body>
</html>
