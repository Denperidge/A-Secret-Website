<?php
// Page that clears session and redirects to index
session_start();  // You have to start the session to clear it
session_destroy();
header("Location: index.php", true, 302);
?>