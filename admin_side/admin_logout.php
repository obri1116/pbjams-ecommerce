<!DOCTYPE html>
<html lang="en">
<?php
  // Start the session
  session_start();

  // Destroy the session
  session_destroy();

  // Redirect to login.php
  header('Location: admin_login.php');
  exit;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
</head>
<body>
    
</body>
</html>