<?php

function getConnection() {
    
    $servername = 'localhost';
    $username = 'sa';
    $password = '543219876Dk';
    $database = 'labprog';

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}
?>
