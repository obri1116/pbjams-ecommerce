<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];
    $product_id = $_POST['p_id'];
    $new_quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
    $new_personalization = isset($_POST['personalization']) ? $_POST['personalization'] : null;

    $user_id = $_SESSION['user_id'];

    if ($action == 'remove') {
        $sql = "DELETE FROM cart WHERE p_id = ? AND u_id = ? AND cart_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $product_id, $user_id, $cart_id);
        $success = $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => $success]);
        exit();
    } elseif ($action == 'update') {
        $sql = "UPDATE cart SET quantity = ?, personalization = ? WHERE p_id = ? AND u_id = ? AND cart_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isiii", $new_quantity, $new_personalization, $product_id, $user_id, $cart_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    }

    $conn->close();
}
?>