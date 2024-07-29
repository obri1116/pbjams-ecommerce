<?php

$servername = 'mysql:host=localhost;dbname=client_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO($servername, $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally, set the default fetch mode to FETCH_ASSOC
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

?>
