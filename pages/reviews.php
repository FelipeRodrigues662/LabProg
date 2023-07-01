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

<style>
    section6 {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f1f1f1;
        margin: 20px 300px 20px 300px;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 4px 4px 10px #495757;
    }

    .text {
        margin: 30px;
        display: flex;
        justify-content: center;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    form select {
        margin-top: 10px;
        margin-bottom: 20px;
        padding: 8px;
        border-radius: 10px;
        border: 0.5px solid;
        background-color: #f8f8f8;
        color: black;
    }

    form textarea {
        padding: 10px;
        margin-top: 10px;
        border-radius: 8px;
        border: 0.5px solid;
    }

    p {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    span {
        font-weight: bold;
    }

    .img-back {
        width: 40px;
        margin-left: 50px;
        transition: 0.9s;
    }

    section7 {
        display: flex;
        justify-content: center;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        gap: 30px;
        margin: 10px;
    }

    .review {
        width: 300px;
        display: flex;
        color: #f1f1f1;
        background-color: #495757;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 4px 4px 10px #495757;
        flex-direction: column;
        align-items: flex-start;
    }

    .img-back:hover {
        transform: scale(1.1);
    }

    .btn-submit {
        margin-top: 20px;
        width: 250px;
        background-color: transparent;
        padding: 10px;
        border-radius: 8px;
        border: none;
        background-color: #014bfd;
        color: #f1f1f1;
        cursor: pointer;
    }

    .btn-submit:hover {
        background-color: #3858e9;
    }
</style>

<!DOCTYPE html>
<html>

<head>
    <title>Event Reviews</title>
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
        <h2>Event Reviews</h2>
        <section7>

            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <?php
                    $reviewer = User::getById($review['user_id']);
                    ?>
                    <div class="review">
                        <p><span>User: </span>
                            <?php echo $reviewer->getName(); ?>
                        </p>
                        <p><span>Score: </span>
                            <?php echo $review['score']; ?>
                        </p>
                        <p><span>Comment: </span>
                            <?php echo $review['comment']; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews available for this event.</p>
            <?php endif; ?>
            <br>
            <?php if ($user): ?>
            </section7>
            <section6>
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
                    <textarea placeholder="Comment" name="comment" id="comment" rows="4"></textarea>
                    <button class="btn-submit" type="submit">Submit Review</button>
                </form>
            </section6>
            <a href="./user_profile.php"><img class="img-back" src="../assets/back.png" title="back"></a>
        <?php else: ?>
            <p>You need to be logged in to add a review.</p>
        <?php endif; ?>
    </section>
</body>

</html>