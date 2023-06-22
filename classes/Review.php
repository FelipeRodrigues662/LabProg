<?php
require_once __DIR__ . '/../database/connection.php';

class Review {
    private $id;
    private $userId;
    private $eventId;
    private $score;
    private $comment;

    public function __construct($userId, $eventId, $score, $comment) {
        $this->userId = $userId;
        $this->eventId = $eventId;
        $this->score = $score;
        $this->comment = $comment;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getEventId() {
        return $this->eventId;
    }

    public function getScore() {
        return $this->score;
    }

    public function getComment() {
        return $this->comment;
    }

    // Setters
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setEventId($eventId) {
        $this->eventId = $eventId;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    // Database interaction methods
    public function save() {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, event_id, score, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $this->userId, $this->eventId, $this->score, $this->comment);
        $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
    }

    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $review = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $review ? new Review($review['user_id'], $review['event_id'], $review['score'], $review['comment']) : null;
    }

    public static function getReviewsByEventId($event_id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = array();
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $reviews;
    }
    
    public static function deleteById($eventId) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM reviews WHERE event_id = ?");
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
?>
