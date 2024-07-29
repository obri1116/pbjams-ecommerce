<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'client_db';

// Db Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_GET['e_id'])) {
    $e_id = intval($_GET['e_id']);
    
    // Fetch gallery details
    $stmt = $conn->prepare("SELECT e_title, e_caption FROM gallery WHERE e_id = ?");
    $stmt->bind_param("i", $e_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_assoc();

    // Fetch gallery images
    $images_stmt = $conn->prepare("SELECT img_event FROM gallery_images WHERE e_id = ?");
    $images_stmt->bind_param("i", $e_id);
    $images_stmt->execute();
    $images_result = $images_stmt->get_result();

    $images = [];
    while ($row = $images_result->fetch_assoc()) {
        $images[] = base64_encode($row['img_event']);
    }

    $details['images'] = $images;

    echo json_encode($details);
}
?>
