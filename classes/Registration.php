<?php
require_once __DIR__ . '/../database/connection.php';

class Registration {
    private $id;
    private $userId;
    private $eventId;
    private $paymentStatus;

    public function __construct($userId, $eventId, $paymentStatus) {
        $this->userId = $userId;
        $this->eventId = $eventId;
        $this->paymentStatus = $paymentStatus;
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

    public function getPaymentStatus() {
        return $this->paymentStatus;
    }

    // Setters
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setEventId($eventId) {
        $this->eventId = $eventId;
    }

    public function setPaymentStatus($paymentStatus) {
        $this->paymentStatus = $paymentStatus;
    }

    // Database interaction methods
    public function save() {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id, payment_status) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $this->userId, $this->eventId, $this->paymentStatus);
        $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
    }

    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM registrations WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $registration = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $registration ? new Registration($registration['user_id'], $registration['event_id'], $registration['payment_status']) : null;
    }
}
?>
