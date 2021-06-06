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
    $conn->close();
    $page = basename(__FILE__);
    header("Location: $page", true, 303);
    exit;
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

<style>
/* Left to right */
@keyframes ltr {
    from { right: 100%; }
    to { right: 0%; }
}
/* Right to left */
@keyframes rtl {
    from { left: 100%; }
    to { left: 0%; }
}

.secret {
    position: absolute;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: clip;

}

<?php 
$animations = ['ltr', 'rtl'];
// nth-type starts counting from 1 and includes the last index
for ($i=1; $i<=$secret_shared_count; $i++) {
    $ltrOrRtl = ($animations);

    $animation_name = $animations[array_rand($animations)];
    if ($animation_name == 'ltr') {
        $start = 'right: 100%;';
        $align = 'right';
    } else {
        $start = 'left: 100%;';
        $align = 'left';
    }
    $animation_duration = rand(25, 80);
    // Make larger delays possible with larger secret amounts,
    // This way, there won't be a gigantic wave or delay or the like
    $animation_delay = rand(1*$i, 2*$i); 
    $top = rand(5, 85);

    echo 
        ".secret:nth-of-type($i) {
            top: ${top}%;
            $start
            text-align: $align;
            direction: $animation_name;
            animation-name: $animation_name;
            animation-duration: ${animation_duration}s;
            animation-delay: ${animation_delay}s;
        }";
}
?>

</style>


<footer>
    <p>Zoveel mensen deelden een secret: <?php echo "$secret_shared_count ($secret_shared_pct%)" ?></p>
    <p>Zoveel mensen deelden geen secret: <?php echo "$no_secret_shared_count ($no_secret_shared_pct%)" ?></p>
</footer>

<?php include('template_bot.php'); ?>