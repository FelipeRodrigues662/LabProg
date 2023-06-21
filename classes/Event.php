<?php
require_once __DIR__ . '/../database/connection.php';

class Event {
    private $id;
    private $title;
    private $description;
    private $date;
    private $time;
    private $location;
    private $categoryId;
    private $price;
    private $images;
    private $paymentStatus;

    public function __construct($id, $title, $description, $date, $time, $location, $categoryId, $price, $images) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->time = $time;
        $this->location = $location;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->images = $images;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getImages() {
        return $this->images;
    }

    public function getPaymentStatus() {
        return $this->paymentStatus;
    }

    // Setters

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setImages($images) {
        $this->images = $images;
    }

    public function setPaymentStatus($paymentStatus) {
        $this->paymentStatus = $paymentStatus;
    }

    // Database interaction methods
    public function save() {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, category_id, price, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisS", $this->title, $this->description, $this->date, $this->time, $this->location, $this->categoryId, $this->price, $this->images);
        $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
    }

    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $event ? new Event($event['id'], $event['title'], $event['description'], $event['date'], $event['time'], $event['location'], $event['category_id'], $event['price'], $event['images']) : null;
    }

    public static function getAll() {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM events");
        $stmt->execute();
        $result = $stmt->get_result();
        $events = [];
        while ($event = $result->fetch_assoc()) {
            $events[] = new Event($event['id'], $event['title'], $event['description'], $event['date'], $event['time'], $event['location'], $event['category_id'], $event['price'], $event['images']);
        }
        $stmt->close();
        $conn->close();
        return $events;
    }

    public static function getEventsByCategory($categoryId) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, title, description, date, time, location, category_id, price, images FROM events WHERE category_id = ?");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $events = [];
        while ($event = $result->fetch_assoc()) {
            $events[] = $event;
        }
        $stmt->close();
        $conn->close();
        return $events;
    }

    public static function deleteById($id) {
        // Excluir registros de inscrição relacionados ao evento
        Registration::deleteById($id);

        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}