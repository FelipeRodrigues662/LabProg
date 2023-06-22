<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Event.php';
require_once '../classes/Review.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$event_id = null;
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
}

$event = null;
$reviews = [];
if ($event_id) {
    $event = Event::getById($event_id);
    $reviews = Review::getReviewsByEventId($event_id);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Reviews</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <style>
        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .review p {
            margin: 0;
        }

        .review p:first-child {
            font-weight: bold;
        }

        .add-review-form {
            margin-top: 20px;
        }

        .add-review-form label,
        .add-review-form textarea,
        .add-review-form select {
            display: block;
            margin-bottom: 10px;
        }

        .add-review-form button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Event Reviews</h1>
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
        <h2>Event Reviews</h2>
        <?php if (!empty($reviews)) : ?>
            <?php foreach ($reviews as $review) : ?>
                <?php
                // Obtenha o objeto User correspondente ao ID do usuário da avaliação
                $reviewer = User::getById($review['user_id']);
                ?>
                <div class="review">
                    <p>User: <?php echo $reviewer->getName(); ?></p>
                    <p>Score: <?php echo $review['score']; ?></p>
                    <p>Comment: <?php echo $review['comment']; ?></p>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No reviews available for this event.</p>
        <?php endif; ?>
        <br>
        <?php if ($user) : ?>
            <h3>Add Review</h3>
            <form class="add-review-form" action="../services/add_review.php" method="post">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <label for="score">Score:</label>
                <select name="score" id="score">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <label for="comment">Comment:</label>
                <textarea name="comment" id="comment" rows="4"></textarea>
                <button type="submit">Submit Review</button>
            </form>
        <?php else : ?>
            <p>You need to be logged in to add a review.</p>
        <?php endif; ?>
    </section>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Event Reviews. All rights reserved.</p>
    </footer>
</body>
</html>
