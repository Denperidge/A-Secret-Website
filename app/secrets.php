<?php

include('env.php');

$conn = mysqli_connect($SQL_URL, $SQL_USER, $SQL_PASS, $SQL_DB);
if (!$conn) {
    die('Connection failed ' . mysqli_connect_error() . ' ' . mysqli_connect_errno() . '.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $create_sql = "CREATE TABLE IF NOT EXISTS Secrets (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        shared BOOLEAN NOT NULL,
        content VARCHAR($SECRET_MAX_LENGTH) NULL
    )";

    $shared = intval($_POST['share']);  // Record the radiobutton value
    // Depending on the radiobutton value, record the secret
    if ($shared == 1) {
        $content = preg_replace($SECRET_ALLOWED_CHARS_PHP, '', $_POST['secret']);
    } else if ($shared == 0) {
        $content = null;
    } else {
        exit("Invalid input!");
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
}

// Get data to display, whether it's post or get
$secretsSql = "SELECT Content FROM Secrets WHERE shared = 1";
$nosecretsSql = "SELECT COUNT(*) AS Total FROM Secrets WHERE shared = 0";

function get_shared($conn, $sql) {
    if ($result = $conn->query($sql)) {
        /*
        while ($row = $result->fetch_assoc()) {
            // If Total count, return that
            if (isset($row['Total'])) return $row['Total'];
            $row['Content'];
        }*/
        return $result;
    } else {
        return 'Error getting shared';
    }
}


$raw_secrets = get_shared($conn, $secretsSql);
$secret_shared_count = $raw_secrets->num_rows;
$no_secret_shared_count = get_shared($conn, $nosecretsSql)->fetch_assoc()['Total'];
$totaltotal = $secret_shared_count + $no_secret_shared_count; 
$secret_shared_pct = round($secret_shared_count / $totaltotal * 100, 2);
$no_secret_shared_pct = round($no_secret_shared_count / $totaltotal * 100, 2);


$conn->close(); 

$secrets = [];

while ($result = $raw_secrets->fetch_assoc()) {
    $secrets[] = $result['Content'];
}

shuffle($secrets);

foreach ($secrets as $secret) {
    echo "<span class=\"secret\">$secret</span>";
}

?>

<?php include('template_top.php'); ?>


<footer>
    <p>Zoveel mensen deelden een secret: <?php echo "$secret_shared_count ($secret_shared_pct%)" ?></p>
    <p>Zoveel mensen deelden geen secret: <?php echo "$no_secret_shared_count ($no_secret_shared_pct%)" ?></p>
</footer>

<?php include('template_bot.php'); ?>