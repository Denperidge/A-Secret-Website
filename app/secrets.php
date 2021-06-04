<?php include('template_top.php'); ?>

<?php

include('env.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $create_sql = "CREATE TABLE IF NOT EXISTS Secrets (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        shared BOOLEAN NOT NULL,
        content VARCHAR($SECRET_MAX_LENGTH) NULL
    )";

    $shared = intval($_POST['share']);  // Record the radiobutton value
    // Depending on the radiobutton value, record the secret
    if ($shared == 1) {
        $content = $_POST['secret'];
    } else {
        $content = null;
    }

    $conn = mysqli_connect($SQL_URL, $SQL_USER, $SQL_PASS, $SQL_DB);
    if (!$conn) {
        die('Connection failed ' . mysqli_connect_error() . ' ' . mysqli_connect_errno() . '.');
    }
    // Create table if it doesn't exist
    if (!$conn->query($create_sql)) {
        // And show an error if need be
        die($conn->error);
    }

    $statement = $conn->prepare('INSERT INTO Secrets (shared, content) VALUES (?, ?)');
    $statement->bind_param('is', $shared, $content);
    $statement->execute();

    $statement->close();
    $conn->close();
}
?>

<?php include('template_bot.php'); ?>
