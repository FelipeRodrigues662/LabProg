<?php
require_once __DIR__ . '/../database/connection.php';

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
        // Salva o evento no banco de dados
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, category_id, price, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssiss', $title, $description, $date, $time, $location, $categoryId, $price, $images);

        
        if ($stmt->execute()) {
            // Evento inserido com sucesso
            $event_id = $stmt->insert_id;
            echo "Event inserted successfully. Event ID: " . $event_id;
            // Redireciona para a página de lista de eventos ou exibe uma mensagem de sucesso
            // header('Location: event_list.php');
            // exit();
        } else {
            // Erro ao inserir evento
            echo "Error inserting event: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!-- Resto do código HTML permanece igual -->

<!DOCTYPE html>
<html>
<head>
    <title>Event Management System - Add Event</title>
    <link rel="stylesheet" type="text/css" href="../css/add_event.css">
</head>
<body>
    <header>
        <h1>Event Management System</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./add_event.php">Add Event</a></li>
            <li><a href="./user_login.php">Login</a></li>
            <li><a href="./user_registration.php">Register</a></li>
        </ul>
    </nav>
    
    <section>
        <h2 >Add Event</h2>
        <!-- Formulário para adicionar um evento -->
        <form action="add_event.php" method="POST">
            <div>
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
            </div>
            <div>
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required>
            </div>
            <div>
                <label for="time">Time:</label>
                <input type="time" name="time" id="time" required>
            </div>
            <div>
                <label for="location">Location:</label>
                <input type="text" name="location" id="location" required>
            </div>
            <div>
                <label for="category">Category:</label>
                <!-- Exemplo de como obter dinamicamente as categorias do banco de dados -->
                <?php
                require_once '../classes/Category.php'; // Inclua a classe Category

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
            <div>
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" step="0.01" required>
            </div>
            <div>
                <label for="images">Image Link:</label>
                <input type="url" name="images" id="images" required>
            </div>
            <div>
                <input type="submit" value="Add Event">
            </div>
        </form>
    </section>
    
    <footer>

    </footer>
</body>
</html>
