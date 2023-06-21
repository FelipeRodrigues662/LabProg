<?php
require_once __DIR__ . '/../database/connection.php';

class Category {
    private $id;
    private $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public static function getAll() {
        $conn = getConnection();
        $sql = "SELECT id, name FROM categories";
        $result = $conn->query($sql);

        $categories = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $category = new Category($row['id'], $row['name']);
                $categories[] = $category;
            }
        }

        $conn->close();

        return $categories;
    }
}
?>
