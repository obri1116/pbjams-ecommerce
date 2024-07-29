<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'client_db';

// Db Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE o_id = ?");
    $stmt->bind_param("si", $status, $order_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo 'Order status updated successfully';
    } else {
        echo 'Error updating order status: ' . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
