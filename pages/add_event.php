<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../classes/User.php';

session_start();

if (!isset($_SESSION['user']) || (unserialize($_SESSION['user'])->getUserType() !== 'admin' && unserialize($_SESSION['user'])->getUserType() !== 'grant_admin')) {
    header('Location: ../services/unauthorized.php');
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $categoryId = $_POST['category'];
    $price = $_POST['price'];
    $images = $_POST['images'];

    // Validação dos dados
    $errors = [];

    // Verifica se o título está vazio
    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    // Verifica se a descrição está vazia
    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    // Verifica se a data está vazia ou não é um formato válido (yyyy-mm-dd)
    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = "Invalid date format. Please enter a valid date (yyyy-mm-dd).";
    }

    // Verifica se o horário está vazio ou não é um formato válido (hh:mm)
    if (empty($time) || !preg_match('/^\d{2}:\d{2}$/', $time)) {
        $errors[] = "Invalid time format. Please enter a valid time (hh:mm).";
    }

    // Verifica se a localização está vazia
    if (empty($location)) {
        $errors[] = "Location is required.";
    }

    // Verifica se a categoria é um número inteiro positivo
    if (!ctype_digit($categoryId) || $categoryId <= 0) {
        $errors[] = "Invalid category.";
    }

    // Verifica se o preço é um número decimal positivo
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Invalid price.";
    }

    // Verifica se o link das imagens é uma URL válida
    if (!filter_var($images, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid image link.";
    }

    // Se houver erros, exibe as mensagens de erro
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    } else {

        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, category_id, price, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssiss', $title, $description, $date, $time, $location, $categoryId, $price, $images);

        if ($stmt->execute()) {

            $event_id = $stmt->insert_id;
            echo "Event inserted successfully. Event ID: " . $event_id;
        } else {

            echo "Error inserting event: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Event</title>
    <link rel="stylesheet" href="../CSS/add_event.css">
</head>

<body>
    <header>
        <h1></h1>
        <nav>
            <ul>
                <li><a href="./index.php"><img class="home-page" src="../assets/home.png" alt="HomePage"></a></li>
                <?php if ($user instanceof User && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')): ?>
                    <li><a class="register-event" href="../pages/add_event.php">Add Event</a></li>
                <?php endif; ?>
                <?php if ($user instanceof User): ?>
                    <li><a class="register-event" href="../pages/process_registration.php">Registrar evento</a></li>
                    <li><a href="../pages/user_profile.php"><img class="perfil-img" src="../assets/perfil.png" alt=""></a>
                    </li>
                    <li><a href="../services/logout.php"><img class="leave-img" src="../assets/sair.png" alt=""></a></li>
                <?php else: ?>
                    <li><a href="../pages/user_login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>



    <section>
        <h2>Add Event</h2>
        <!-- Formulário para adicionar um evento -->

        <form class="formulario-event" action="./add_event.php" method="POST">
            <div1>
                <label for="title">Title:</label>
                <input placeholder="Title" type="text" name="title" id="title" required>
            </div1>
            <div1>
                <label for="description">Description:</label>
                <input placeholder="Description" name="description" id="description" required></input>
            </div1>
            <div1>
                <label for="date">Date:</label>
                <input placeholder="Date" type="date" name="date" id="date" required>
            </div1>
            <div1>
                <label for="time">Time:</label>
                <input type="time" placeholder="Time" name="time" id="time" required>
            </div1>
            <div1>
                <label for="location">Location:</label>
                <input placeholder="Location" type="text" name="location" id="location" required>
            </div1>
            <div1>
                <label for="category">Category:</label>
                <?php
                require_once __DIR__ . '/../classes/Category.php';

                $categories = Category::getAll();

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
            </div1>
            <div1>
                <label for="price">Price:</label>
                <input placeholder="Price" type="number" name="price" id="price" step="0.01" required>
            </div1>
            <div1>
                <label for="images">Image Link:</label>
                <input placeholder="Image Link" type="url" name="images" id="images" required>
            </div1>
            <div1>
                <input type="submit" class="btn-addevent" value="Add Event">
            </div1>
        </form>
    </section>

    <footer>

    </footer>
</body>

</html>